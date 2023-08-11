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
                'Add Product',
                'Edit Product',
                'Delete Product',
                'Publish Product',
                'Hide Product',
                'View Product',
                'Export Product',
                'Import Product',
                'Preview Product'
            ],
            'Orders' => [
                'Add New Order',
                'Delete Order',
                'View Order',
                'View Order Details',
                'Order Status'
            ],
            'Customers' => [
                'Add Customer',
                'Edit Customer',
                'View Customer Profile',
                'Delete Customer',
                'View Customers',
                'Add Customer Address',
                'Edit Customer Address',
                'View Customer Addresses',
                'Delete Customer Address'
            ],
            'Categories' => [
                'Add Category',
                'Edit Category',
                'Publish Category',
                'Hide Category',
                'View Category',
                'Delete Category'
            ],
            'Brands' => [
                'Add Brand',
                'Edit Brand',
                'Publish Brand',
                'Hide Brand',
                'View Brand',
                'Delete Brand'
            ],
            'Marketing' => [
                'Add Coupon',
                'Edit Coupon',
                'View Coupon',
                'Delete Coupon',
                'Navbar',
                'Hide Coupon',
                'Publish Coupon'
            ],
            'Settings' => [
                'View Settings',
                'Store Settings',
                'View Payment Methods',
                'View Shipping Methods',
                'Design Store',
                'Definetion Pages',
                'Team Members',
                'Tax',
                'Custom Domain',
                'Link Services',
                'Additional Store Settings',
                'SMS Settings',
                'User Notifications',
                'SEO Settings',
                'Invoice Settings',
            ],
            'Reports' => [
                'View Reports'
            ],
            'Affiliate Marketing' => [
                'Add Affiliate Marketer',
                'Edit Affiliate Marketer',
                'View Affiliate Marketer',
                'Delete Affiliate Marketer',
                'Settle Affiliate Marketer',
                'Publish Affiliate Marketer'
            ],
            'Abandoned Carts' => [
                'View Abandoned Carts'
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
