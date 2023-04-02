<?php

return [
    'terms' => [
        'item_master_data' => "Master Data"
    ],
    'admin-user' => [
        'title' => 'Users',

        'actions' => [
            'index' => 'Users',
            'create' => 'New User',
            'edit' => 'Edit :name',
            'edit_profile' => 'Edit Profile',
            'edit_password' => 'Edit Password',
        ],

        'columns' => [
            'id' => 'ID',
            'last_login_at' => 'Last login',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Password Confirmation',
            'activated' => 'Activated',
            'forbidden' => 'Forbidden',
            'language' => 'Language',

            //Belongs to many relations
            'roles' => 'Roles',

        ],
    ],

    'item-data' => [
        'title' => 'Item Data',

        'actions' => [
            'index' => 'Item Data',
        ],
    ],

    'brand' => [
        'title' => 'Brands',

        'actions' => [
            'index' => 'Brands',
            'create' => 'New Brand',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',

        ],
    ],

    'type' => [
        'title' => 'Types',

        'actions' => [
            'index' => 'Types',
            'create' => 'New Type',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',

        ],
    ],

    'item' => [
        'title' => 'Items',

        'actions' => [
            'index' => 'Items',
            'create' => 'New Item',
            'edit' => 'Edit :name',
            'export' => 'Export Data'
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'brand_id' => 'Brand',
            'type_id' => 'Type',
            'price_id' => 'Price',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',

        ],
    ],

    'branch' => [
        'title' => 'Branches',

        'actions' => [
            'index' => 'Branches',
            'create' => 'New Branch',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',

        ],
    ],

    'price' => [
        'title' => 'Price Management',

        'actions' => [
            'index' => 'Prices',
            'create' => 'New Price',
            'export' => 'Export',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'cut_amount' => 'Cutting Price',
            'box_amount' => 'Box Price',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',

        ],
    ],

    'receiving' => [
        'title' => 'Receiving Module',

        'actions' => [
            'index' => 'Receiving Module',
            'line_item' => 'Inventory Detail',
            'create' => 'New Receiving Transaction',
            'export' => 'Export',
            'edit' => 'Receiving Transaction',
        ],

        'columns' => [
            'id' => 'ID',
            'ref_no' => 'Reference Number',
            'cut_amount' => 'Cutting Price',
            'transaction_type_id' => 'Transaction Type',
            'transaction_date' => 'Transaction Date',
            'branch_id' => 'Branch',
            'total_weight' => 'Weight',
            'total_amount' => 'Expense',
            'received_by' => 'Received By',
            'remarks' => 'Remarks',

        ],
    ],

    'delivery' => [
        'title' => 'Delivery Module',

        'actions' => [
            'index' => 'Delivery Module',
            'line_item' => 'Inventory Detail',
            'create' => 'New Delivery Transaction',
            'export' => 'Export',
            'edit' => 'Delivery Transaction',
        ],

        'columns' => [
            'id' => 'ID',
            'ref_no' => 'Reference Number',
            'cut_amount' => 'Cutting Price',
            'transaction_type_id' => 'Transaction Type',
            'transaction_date' => 'Transaction Date',
            'branch_id' => 'Branch',
            'total_weight' => 'Weight',
            'total_amount' => 'Expense',
            'received_by' => 'Received By',
            'delivered_by' => 'Delivered By',
            'remarks' => 'Remarks',

        ],
    ],

    'sales' => [
        'title' => 'Sales Module',

        'actions' => [
            'index' => 'Sales Module',
            'line_item' => 'Particulars',
            'create' => 'New Sales Transaction',
            'export' => 'Export',
            'edit' => 'Sales Transaction',
        ],

        'columns' => [
            'id' => 'ID',
            'ref_no' => 'Reference Number',
            'customer' => 'Customer',
            'cut_amount' => 'Cutting Price',
            'transaction_type_id' => 'Transaction Type',
            'transaction_date' => 'Transaction Date',
            'branch_id' => 'Branch',
            'created_by' => 'Created By',
            'total_amount' => 'Amount',
            'customer_category' => 'Customer Category',

        ],
    ],

    'transaction-detail' => [
        'title' => 'Inventory Details',

        'actions' => [
            'index' => 'Inventory Details',
            'create' => 'New Inventory Detail',
            'export' => 'Export',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'ref_no' => 'Reference Number',
            'item_id' => 'Item',
            'qr_code' => 'QR Code',
            'quantity' => 'Weight',
            'current_weight' => 'Current Stock',
            'amount' => 'Amount',
            'sale_type' => 'Sale Type',
            'price' => 'Price',

        ],
    ],

    'access-tier' => [
        'title' => 'Access Tags',

        'actions' => [
            'index' => 'Access Tags',
            'create' => 'New Access Tag',
            'export' => 'Export',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'tier_id' => 'Access Tier',
            'user_id' => 'User',
            'branch_id' => 'Branch',

        ],
    ],

    'transfer' => [
        'title' => 'Transfer Module',

        'actions' => [
            'index' => 'Transfer Transaction',
            'create' => 'New Transfer',
            'export' => 'Export',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'pullout_transaction_id' => 'Pullout Transaction',
            'delivery_transaction_id' => 'Delivery Transaction',
            'created_by' => 'Created By'
        ],
    ],

    'pullout' => [
        'title' => 'Transfer Module',

        'actions' => [
            'index' => 'Pullout Module',
            'line_item' => 'Inventory Detail',
            'create' => 'New Pullout Transaction',
            'export' => 'Export',
            'edit' => 'Pullout Transaction',
        ],

        'columns' => [
            'id' => 'ID',
            'ref_no' => 'Reference Number',
            'cut_amount' => 'Cutting Price',
            'transaction_type_id' => 'Transaction Type',
            'transaction_date' => 'Transaction Date',
            'branch_id' => 'Branch',
            'total_weight' => 'Weight',
            'total_amount' => 'Expense',
            'received_by' => 'Received By',
            'remarks' => 'Remarks',

        ],
    ],

    'customer' => [
        'title' => 'Customers',

        'actions' => [
            'index' => 'Customers',
            'create' => 'New Customer',
            'export' => 'Export',
            'edit' => 'Edit Customer',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Customer Name',
            'agent_ids' => 'Traders',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',

        ],

    ],

    'trader' => [
        'title' => 'Traders',

        'actions' => [
            'index' => 'Traders',
            'create' => 'New Trader',
            'export' => 'Export',
            'edit' => 'Edit Trader',
        ],

        'columns' => [
            'id' => 'ID',
            'trader_name' => 'Trader Name',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',

        ],




    ],
    'expense' => [
        'title' => 'Expenses',

        'actions' => [
            'index' => 'Expenses',
            'create' => 'New Expense',
            'export' => 'Export',
            'edit' => 'Edit Expense',
        ],

        'columns' => [
            'id' => 'ID',
            'expense_name' => 'Expense Name',

        ],
    ]

    // Do not delete me :) I'm used for auto-generation
];
