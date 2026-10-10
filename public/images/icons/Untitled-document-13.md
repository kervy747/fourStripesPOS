Schema::create('password\_reset\_tokens', function (Blueprint \$table) {  
            \$table\-\>string('email')-\>primary();  
            \$table\-\>string('token');  
            \$table\-\>timestamp('created\_at')-\>nullable();  
        });

        Schema::create('sessions', function (Blueprint \$table) {  
            \$table\-\>string('id')-\>primary();  
            \$table\-\>foreignId('user\_id')-\>nullable()-\>index();  
            \$table\-\>string('ip\_address', 45)-\>nullable();  
            \$table\-\>text('user\_agent')-\>nullable();  
            \$table\-\>longText('payload');  
            \$table\-\>integer('last\_activity')-\>index();  
        });  
    }

Schema::create('products', function (Blueprint \$table) {  
            \$table\-\>id();  
            \$table\-\>string('item\_code')-\>unique();  
            \$table\-\>string('name');  
            \$table\-\>enum('category', \['machines', 'tools', 'accessories'\]);  
            \$table\-\>integer('quantity')-\>default(0);  
            \$table\-\>integer('reorder\_level')-\>default(5);  
            \$table\-\>decimal('unit\_cost', 10, 2)-\>default(0);  
            \$table\-\>decimal('price', 10, 2);  
            \$table\-\>text('description')-\>nullable();  
            \$table\-\>timestamps();  
        });  
    }

  Schema::create('customers', function (Blueprint \$table) {  
                \$table\-\>id();  
                \$table\-\>string('name');  
                \$table\-\>string('phone\_number')-\>nullable();  
                \$table\-\>text('address');

                \$table\-\>timestamps();  
            });  
    }

   Schema::create('sales', function (Blueprint \$table) {  
            \$table\-\>id();  
            \$table\-\>foreignId('customer\_id')-\>constrained()-\>cascadeOnDelete();  
            \$table\-\>foreignId('user\_id')-\>constrained()-\>cascadeOnDelete();  
            \$table\-\>decimal('cash\_received', 10, 2);  
            \$table\-\>decimal('change', 10, 2)-\>default(0);  
            \$table\-\>text('notes')-\>nullable();  
            \$table\-\>decimal('total', 10, 2);

            \$table\-\>timestamps();  
        });  
    }

Schema::create('sale\_items', function (Blueprint \$table) {  
            \$table\-\>id();  
            \$table\-\>foreignId('sale\_id')-\>constrained()-\>cascadeOnDelete();  
            \$table\-\>foreignId('product\_id')-\>nullable()-\>constrained()-\>nullOnDelete();  
            \$table\-\>string('item\_code');  
            \$table\-\>string('item\_name');  
            \$table\-\>integer('quantity');  
            \$table\-\>decimal('price', 10, 2);  
            \$table\-\>decimal('subtotal', 10, 2);

            \$table\-\>timestamps();  
        });  
  Schema::create('audit\_logs', function (Blueprint \$table) {  
            \$table\-\>id();  
            \$table\-\>foreignId('user\_id')-\>nullable()-\>constrained()-\>nullOnDelete();  
            \$table\-\>string('module');  
            \$table\-\>string('action');  
            \$table\-\>text('description');

            \$table\-\>timestamps();  
        });  
    }

      \$table\-\>boolean('is\_active')-\>default(true)-\>after('role');  
        });  
    }

    public function down(): void  
    {  
        Schema::table('users', function (Blueprint \$table) {  
            \$table\-\>dropColumn('is\_active');  
        });  
    }  
 Schema::create('stock\_logs', function (Blueprint \$table) {  
            \$table\-\>id();  
            \$table\-\>foreignId('product\_id')-\>constrained()-\>cascadeOnDelete();  
            \$table\-\>integer('quantity\_added');  
            \$table\-\>text('notes')-\>nullable();  
            \$table\-\>timestamps();  
        });  
    }  
