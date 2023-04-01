<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Brackets\AdminAuth\Models\AdminUser::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt($faker->password),
        'remember_token' => null,
        'activated' => true,
        'forbidden' => $faker->boolean(),
        'language' => 'en',
        'deleted_at' => null,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        'last_login_at' => $faker->dateTime,
        
    ];
});/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Brand::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Brand::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Type::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Item::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'brand_id' => $faker->randomNumber(5),
        'type_id' => $faker->randomNumber(5),
        'price_id' => $faker->randomNumber(5),
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Branch::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Price::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Price::class, static function (Faker\Generator $faker) {
    return [
        'item_id' => $faker->randomNumber(5),
        'price_category' => $faker->randomNumber(5),
        'amount' => $faker->randomFloat,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Price::class, static function (Faker\Generator $faker) {
    return [
        'item_id' => $faker->randomNumber(5),
        'box_amount' => $faker->randomFloat,
        'cut_amount' => $faker->randomFloat,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Item::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'brand_id' => $faker->randomNumber(5),
        'type_id' => $faker->randomNumber(5),
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'email' => $faker->email,
        'email_verified_at' => $faker->dateTime,
        'password' => bcrypt($faker->password),
        'remember_token' => null,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Permission::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'guard_name' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Role::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'guard_name' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\TransactionDetail::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\TransactionDetail::class, static function (Faker\Generator $faker) {
    return [
        'ref_no' => $faker->sentence,
        'transaction_header_id' => $faker->sentence,
        'item_id' => $faker->sentence,
        'qr_code' => $faker->sentence,
        'quantity' => $faker->sentence,
        'amount' => $faker->randomFloat,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\TransactionHeader::class, static function (Faker\Generator $faker) {
    return [
        'ref_no' => $faker->sentence,
        'transaction_type_id' => $faker->randomNumber(5),
        'branch_id' => $faker->sentence,
        'transaction_date' => $faker->date(),
        'received_by' => $faker->sentence,
        'delivered_by' => $faker->sentence,
        'remarks' => $faker->sentence,
        'customer_id' => $faker->sentence,
        'customer_category' => $faker->sentence,
        'payment_id' => $faker->sentence,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\AccessTier::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\AccessTier::class, static function (Faker\Generator $faker) {
    return [
        'tier_id' => $faker->sentence,
        'user_id' => $faker->sentence,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\AccessTier::class, static function (Faker\Generator $faker) {
    return [
        'tier_id' => $faker->sentence,
        'user_id' => $faker->sentence,
        'branch_id' => $faker->sentence,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Customer::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Customer::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'agent_ids' => $faker->sentence,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Transfer::class, static function (Faker\Generator $faker) {
    return [
        'pullout_transaction_id' => $faker->sentence,
        'delivery_transaction_id' => $faker->sentence,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Trader::class, static function (Faker\Generator $faker) {
    return [
        'trader_name' => $faker->sentence,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Expense::class, static function (Faker\Generator $faker) {
    return [
        'expense_name' => $faker->sentence,
        'cost' => $faker->randomFloat,
        'branch_id' => $faker->sentence,
        'remarks' => $faker->sentence,
        'created_by' => $faker->sentence,
        'updated_by' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
