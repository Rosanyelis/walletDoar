<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use ReflectionClass;

class ApiDocsController extends Controller
{
    /**
     * Display a listing of all API routes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $allRoutes = Route::getRoutes();
        $apiRoutes = [];

        foreach ($allRoutes as $route) {
            $uri = $route->uri();
            
            // Filter for API routes
            if (Str::startsWith($uri, 'api/')) {
                $apiRoutes[] = $this->formatRouteData($route);
            }
        }

        // Group routes by version or first segment after 'api'
        $groupedRoutes = collect($apiRoutes)->groupBy(function ($route) {
            $segments = explode('/', $route['uri']);
            return $segments[1] ?? 'general';
        });

        // Read last tracked requests
        $trackedRequests = [];
        $logPath = storage_path('logs/api_tracker.log');
        if (file_exists($logPath)) {
            $lines = file($logPath);
            $lastLines = array_slice($lines, -10);
            foreach ($lastLines as $line) {
                // Extract JSON part from log line (the Log facade adds prefixes)
                if (preg_match('/\{.*\}/', $line, $matches)) {
                    $json = json_decode($matches[0], true);
                    if ($json) {
                        $trackedRequests[] = $json;
                    }
                }
            }
        }
        $trackedRequests = array_reverse($trackedRequests);

        $page_title = setPageTitle("API Documentation & Tracker");
        
        return view('api-docs.index', compact('groupedRoutes', 'page_title', 'trackedRequests'));
    }

    /**
     * Format route data for the documentation view.
     *
     * @param \Illuminate\Routing\Route $route
     * @return array
     */
    protected function formatRouteData($route)
    {
        $action = $route->getActionName();
        $controller = '';
        $method = '';

        if (str_contains($action, '@')) {
            list($controller, $method) = explode('@', $action);
        } elseif (is_string($action) && !str_contains($action, 'Closure')) {
            $controller = $action;
        }

        $parameters = $route->parameterNames();
        $middleware = $route->middleware();

        // Try to get some info from the controller via reflection
        $description = '';
        $validationRules = [];
        $exampleRequest = null;
        $exampleResponse = null;

        if ($controller && class_exists($controller)) {
            try {
                $reflection = new ReflectionClass($controller);
                if ($method && $reflection->hasMethod($method)) {
                    $reflectionMethod = $reflection->getMethod($method);
                    $docComment = $reflectionMethod->getDocComment();
                    
                    if ($docComment) {
                        $description = $this->parseDocComment($docComment);
                    }

                    // Extract method body
                    $source = file($reflectionMethod->getFileName());
                    $startLine = $reflectionMethod->getStartLine() - 1;
                    $endLine = $reflectionMethod->getEndLine();
                    $methodBody = implode("", array_slice($source, $startLine, $endLine - $startLine));
                    
                    $methods = $route->methods();
                    $primaryMethod = strtoupper($methods[0] ?? 'GET');

                    if ($primaryMethod === 'GET') {
                        $exampleResponse = $this->extractResponseExample($methodBody);
                    } else {
                        $validationRules = $this->extractValidationRules($methodBody);
                        if (!empty($validationRules)) {
                            $exampleRequest = $this->generateExample($validationRules, $methods);
                        }
                        // Still try to get response if possible
                        $exampleResponse = $this->extractResponseExample($methodBody);
                    }
                }
            } catch (\Exception $e) {
                // Ignore reflection errors
            }
        }

        $methods = $route->methods();
        $primaryMethod = $methods[0] ?? 'GET';

        return [
            'uri' => $uri = $route->uri(),
            'full_url' => url($uri),
            'methods' => $methods,
            'name' => $route->getName(),
            'action' => $action,
            'controller' => $controller,
            'controller_short' => class_basename($controller),
            'method' => $method,
            'middleware' => $middleware,
            'parameters' => $parameters,
            'description' => $description,
            'validation_rules' => $validationRules,
            'example_request' => $exampleRequest,
            'example_response' => $exampleResponse,
            'color' => $this->getMethodColor($primaryMethod),
        ];
    }

    /**
     * Try to extract response structure keys from Response::success calls.
     *
     * @param string $body
     * @return string|null
     */
    protected function extractResponseExample($body)
    {
        // Match Response::success(message, data, code)
        if (preg_match('/Response::success\([^,]+,\s*\[(.*?)\]\s*[,)]/s', $body, $matches)) {
            $dataRaw = $matches[1];
            preg_match_all('/[\'"]([^\'"]+)[\'"]\s*=>/', $dataRaw, $keyMatches);
            
            $dataArr = [];
            if (!empty($keyMatches[1])) {
                foreach ($keyMatches[1] as $key) {
                    $dataArr[$key] = "...";
                }
            }

            return json_encode([
                "message" => "Success message",
                "data" => !empty($dataArr) ? $dataArr : new \stdClass(),
                "type" => "success"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        return null;
    }

    /**
     * Extract validation rules from method body using regex.
     *
     * @param string $body
     * @return array
     */
    protected function extractValidationRules($body)
    {
        $rules = [];
        // Match Validator::make(..., [ ... ]) or $request->validate([ ... ])
        $patterns = [
            '/Validator::make\([^,]+,\s*\[(.*?)\]\s*\)/s',
            '/\$request->validate\(\s*\[(.*?)\]\s*\)/s',
            '/\$this->validate\([^,]+,\s*\[(.*?)\]\s*\)/s'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $body, $matches)) {
                $rulesRaw = $matches[1];
                // Match 'field' => 'rules' or "field" => "rules"
                preg_match_all('/[\'"]([^\'"]+)[\'"]\s*=>\s*([\'"][^\'"]+[\'"]|[^\s,]+)/', $rulesRaw, $fieldMatches);
                if (!empty($fieldMatches[1])) {
                    foreach ($fieldMatches[1] as $index => $field) {
                        $rules[$field] = trim($fieldMatches[2][$index], "\"'");
                    }
                    break; // Use the first found validation block
                }
            }
        }
        return $rules;
    }

    /**
     * Generate a JSON example based on validation rules.
     *
     * @param array $rules
     * @param array $methods
     * @return string|null
     */
    protected function generateExample($rules, $methods = [])
    {
        $example = [];
        foreach ($rules as $field => $ruleStr) {
            $val = "text";
            if (str_contains($ruleStr, 'numeric') || str_contains($ruleStr, 'integer')) $val = 123;
            if (str_contains($ruleStr, 'email')) $val = "user@example.com";
            if (str_contains($ruleStr, 'image')) $val = "(binary image file)";
            if (str_contains($ruleStr, 'date')) $val = now()->toDateString();
            if (str_contains($ruleStr, 'boolean')) $val = true;
            if (str_contains($ruleStr, 'url')) $val = "https://example.com";
            if (str_contains($ruleStr, 'exists') || str_contains($ruleStr, 'required')) {
                if (str_contains($field, 'id')) $val = 1;
                if (str_contains($field, 'amount')) $val = 50.00;
                if (str_contains($field, 'currency')) $val = "USD";
            }
            $example[$field] = $val;
        }

        if (empty($example)) return null;

        return json_encode($example, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Parse scientific description from doc comment.
     *
     * @param string $docComment
     * @return string
     */
    protected function parseDocComment($docComment)
    {
        $docComment = preg_replace('#^/\*\*|\*/$#', '', $docComment);
        $docComment = preg_replace('#^\s*\*#m', '', $docComment);
        $lines = explode("\n", trim($docComment));
        $description = '';
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Special handling for @example tag
            if (str_starts_with($line, '@example')) {
                // If there's an example in the docblock, it might be captured elsewhere or we can use it
                continue;
            }

            if (str_starts_with($line, '@')) continue; // Skip other tags
            if ($line) $description .= $line . ' ';
        }
        return trim($description);
    }

    /**
     * Get a color code based on the HTTP method.
     *
     * @param string $method
     * @return string
     */
    protected function getMethodColor($method)
    {
        return match (strtoupper($method)) {
            'GET' => 'emerald',
            'POST' => 'blue',
            'PUT', 'PATCH' => 'amber',
            'DELETE' => 'rose',
            default => 'slate',
        };
    }
}
