# Corrección de secretos en PaymentGatewaySeeder

Este documento describe los cambios realizados en el seeder de pasarelas de pago para **eliminar claves y secretos reales** del código y evitar que GitHub (u otros sistemas) bloqueen el push por detección de secretos.

---

## Archivo modificado

- **Ruta:** `database/seeders/Admin/PaymentGatewaySeeder.php`

---

## Motivo del cambio

GitHub Push Protection detecta patrones de API keys y secretos (Stripe, PayPal, etc.). Si alguno de estos valores está en el repositorio, el push es rechazado con un error tipo:

- `Push cannot contain secrets`
- `Stripe Test API Secret Key` (u otro proveedor)

Los valores de credenciales **no deben** estar en el código; deben configurarse después (panel de admin o variables de entorno).

---

## Pasarelas y campos modificados

En el array `$payment_gateways`, los siguientes gateways tenían credenciales con valores reales. Se reemplazaron por **string vacío** `""` en los campos indicados.

| # | Gateway   | Alias        | Campos de credenciales afectados |
|---|-----------|--------------|-----------------------------------|
| 1 | Paypal    | paypal       | `client-id`, `secret-id` |
| 2 | CoinGate  | coingate     | `sandbox-app-token` |
| 3 | QRPay     | qrpay        | `client-id`, `secret-id` |
| 4 | **Stripe**| stripe       | `test-publishable-key`, `test-secret-key` *(este provocaba el bloqueo de GitHub)* |
| 5 | Flutterwave | flutterwave | `public-key`, `secret-key`, `encryption-key` |
| 6 | SSLCOMMERZ | sslcommerz  | `store-id`, `secret-key` (Store Password) |
| 7 | Razorpay  | razorpay    | `key-id`, `secret-key` |
| 8 | Paystack  | paystack    | `secret-key`, `public-key` |
| 9 | Authorize | authorize   | `app-login-id`, `transaction-key`, `signature-key` |

**Nota:** Las URLs (Sandbox URL, Production URL) de CoinGate y QRPay se mantienen; solo se vaciaron tokens y claves.

---

## Cómo aplicar estos cambios (si vuelve a pasar)

1. Abrir `database/seeders/Admin/PaymentGatewaySeeder.php`.
2. En el array `$payment_gateways`, localizar la clave `'credentials'` de cada gateway.
3. El valor de `credentials` es un **JSON en string**. Dentro de cada objeto, buscar los campos `"value"` que contengan:
   - Claves que empiecen por `sk_`, `pk_` (Stripe).
   - Cadenas largas que parezcan Client ID, Secret ID, tokens, etc.
4. Reemplazar ese valor por `""` (string vacío), manteniendo la estructura del JSON.
5. Guardar el archivo y hacer commit + push.

**Ejemplo (Stripe):**

- Antes: `"name":"test-secret-key","value":"sk_test_51NECrl..."`
- Después: `"name":"test-secret-key","value":""`

---

## Después de sembrar

Las credenciales reales se configuran desde el **panel de administración** (Payment Method / pasarelas), no desde el seeder. El seeder solo crea los registros con valores vacíos o placeholders; el usuario debe completar las claves en la app.

---

## Si el push sigue bloqueado

Si el secreto ya estaba en un **commit anterior**, GitHub puede seguir rechazando el push. En ese caso:

1. Corregir el archivo como se indica arriba.
2. Opción A: Hacer un **nuevo commit** con la corrección y volver a hacer push (a veces basta si el remoto aún no tenía ese commit).
3. Opción B: **Reescribir historial** para que ese commit ya no contenga el secreto (`git rebase -i` o similar) y luego `git push --force-with-lease`. Solo si tienes claro el impacto en el repositorio compartido.

**No** uses la opción de GitHub "Allow the secret" para claves reales; el secreto quedaría expuesto en el historial.

---

*Última actualización: corrección aplicada en PaymentGatewaySeeder — todos los valores de credenciales reemplazados por `""`.*
