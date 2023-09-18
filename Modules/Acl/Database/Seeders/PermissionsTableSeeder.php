<?php

namespace Modules\Acl\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Products' => [
                'Create-Product',
                'Edit-Product',
                'Delete-Product',
                'View-Product',
                'Export-Product',
                'Import-Product',
            ],
            'Categories' => [
                'Create-Category',
                'Edit-Category',
                'View-Category',
                'Delete-Category'
            ],
            'Brands' => [
                'Create-Brand',
                'Edit-Brand',
                'View-Brand',
                'Delete-Brand'
            ],
            'Customers' => [
                'Create-Customer',
                'Edit-Customer',
                'Delete-Customer',
                'View-Customer',
            ],
            'Shipping-Methods' =>[
                'Create-Shipping-Method',
                'View-Shipping-Method',
                'Edit-Shipping-Method',
                'Delete-Shipping-Method',
            ],
            'Orders' => [
                'Create-Order',
                'Delete-Order',
                'View-Order',
                'View-Order-Details',
                'Change-Order-Status'
            ],
            'Marketing' => [
                'Create-Coupon',
                'Edit-Coupon',
                'View-Coupon',
                'Delete-Coupon',
            ],
            'Taxes' => [
                'Create-Tax',
                'Edit-Tax',
                'View-Tax',
                'Delete-Tax',
            ],
            'Definition-Pages' => [
                'Create-DefinitionPage',
                'Edit-DefinitionPage',
                'View-DefinitionPage',
                'Delete-DefinitionPage',
            ],
            'Notifications' => [
                'View-Notification',
                'Delete-Notification',
            ],
            'Contact-Messages' => [
                'View-Contact-Message',
                'Delete-Contact-Message',
            ],
        
            'Store-Countries' => [
                'Manage-Store-Countries'
            ],
            'Store-Settings' => [
                'Manage-Store-Settings',
                'Manage-Store-Navbar',
                'Edit-Store-Design',
            ],
        
            'Payment-Methods' => [
                'View-Payment-Methods',
            ],


            
            'Settings' => [
                'Definetion-Pages',
                'Team-Members',
                'Custom-Domain',
                'Link-Services',
                'Additional-Store-Settings',
                'SMS-Settings',
                'User-Notifications',
                'SEO-Settings',
                'Invoice-Settings',
            ],
            'Reports' => [
                'View-Reports'
            ],
            'Affiliate-Marketing' => [
                'Create-Affiliate-Marketer',
                'Edit-Affiliate-Marketer',
                'View-Affiliate-Marketer',
                'Delete-Affiliate-Marketer',
                'Settle-Affiliate-Marketer',
                'Publish-Affiliate-Marketer'
            ],
            'Abandoned-Carts' => [
                'View-Abandoned-Carts'
            ]
        ];

        foreach ($permissions as $section => $sectionPermissions) {
            foreach ($sectionPermissions as $permission) {
                Permission::create([
                    'name' => $permission,
                ]);
            }
        }
    }
}
