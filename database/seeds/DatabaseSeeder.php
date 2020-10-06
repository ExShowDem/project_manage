<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
//        $this->call(UserProjectTableSeeder::class);
//        $this->call(CategorySuppliesSeeder::class);
//        $this->call(SuppliesSeeder::class);
//        $this->call(PlanSeeder::class);
//        $this->call(ItemSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(ReceiverTypesTableSeeder::class);
//        $this->call(OfferBuySeeder::class);
//        $this->call(InvoiceSeeder::class);
//        $this->call(RequestSupplySeeder::class);
//        $this->call(InventorySeeder::class);
//        $this->call(ReceiptInputSeeder::class);
//        $this->call(ReceiptOutputSeeder::class);
//        $this->call(ReceiptTransferSeeder::class);
//        $this->call(TasksTableSeeder::class);
//        $this->call(LinksTableSeeder::class);
        $this->call(ResourceTypesTableSeeder::class);
//        $this->call(ResourcesTableSeeder::class);
    }
}
