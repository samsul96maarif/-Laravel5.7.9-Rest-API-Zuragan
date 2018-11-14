<?php

use Illuminate\Database\Seeder;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('subscriptions')->insert([
          'name' => 'hobbyist plan',
          'price' => 140000,
          'num_invoices' => 10,
          'num_users' => 10
      ]);
      DB::table('subscriptions')->insert([
          'name' => 'freelancer plan',
          'price' => 285000,
          'num_invoices' => 15,
          'num_users' => 15
      ]);
      DB::table('subscriptions')->insert([
          'name' => 'studio plan',
          'price' => 435000,
          'num_invoices' => 20,
          'num_users' => 20
      ]);
      DB::table('subscriptions')->insert([
          'name' => 'agency plan',
          'price' => 885000,
          'num_invoices' => 25,
          'num_users' => 25
      ]);
    }
}
