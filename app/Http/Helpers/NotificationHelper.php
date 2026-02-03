<?php

namespace App\Http\Helpers;

use Exception;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminRole;
use App\Models\Admin\AdminHasRole;
use App\Models\Admin\AdminNotification;

class NotificationHelper {

    /**
     * Store permission routes that is uses for sending notification to admin
     */
    public array $permission_routes;

    /**
     * store admin db notification content
     */
    public array $admin_db_notification_data;


    /**
     * prepare for sending notification to admin
     * @param string|array $permission_routes
     * @return self
     */
    public function admin(string | array $permission_routes): self
    {


        if(is_string($permission_routes)) {
            $permission_routes = [$permission_routes];
        }

        $this->permission_routes = $permission_routes;
        return $this;
    }

    /**
     * Get admins id based on the permission routes
     * @return array $admins_id
     */
    public function getPermissionAdminsId():array
    {
        $admins_id = AdminHasRole::join('admin_role_permissions', 'admin_has_roles.admin_role_id', '=', 'admin_role_permissions.admin_role_id')
                    ->join('admin_role_has_permissions', 'admin_role_permissions.id', '=', 'admin_role_has_permissions.admin_role_permission_id')
                    ->whereIn('admin_role_has_permissions.route', $this->permission_routes)
                    ->pluck('admin_has_roles.admin_id')
                    ->toArray();

        return $admins_id;
    }

    /**
     * get user using model
     * @param mixed $model - It should be user model or admin model
     * @param array $users_id = It should be model users id
     * @return mixed $users - model users
     */
    public function getUsersFromId(mixed $model, array $users_id):mixed
    {
        $admin_roles = AdminRole::superAdmin()->active()->first();
        $super_admin = $model::where('id',$admin_roles->admin_id)->first();
        $users = $model::whereIn('id', $users_id)->get();
        // Append the super admin to the users array if it exists
        if ($super_admin) {
            $users->push($super_admin);
        }
        return $users;
    }

    /**
     * Store Admin Db notifications
     * @param mixed $notification_via - It should be laravel notification class or notification template key
     * @return self
     */
    public function adminDbContent(mixed $data): self
    {
        $this->admin_db_notification_data = $data;
        return $this;
    }

    /**
     * Send notification
     */
    public function send()
    {
        //admin db notifications
        try{
            if(!empty($this->admin_db_notification_data)){
                $this->storeAdminNotification();
            }
        }catch(Exception $e){}


    }

     /**
     * Store Admin notification data
     */
    public function storeAdminNotification(){
        $admins = $this->getUsersFromId(Admin::class, $this->getPermissionAdminsId());
        $data = $this->admin_db_notification_data;
        foreach($admins as $admin){
            $data['image'] = get_image($admin->image,'admin-profile','profile');
            AdminNotification::create([
                'type'      =>  $data['type'],
                'admin_id'  => $admin->id,
                'message'   => $data,
            ]);
        }
    }
}
