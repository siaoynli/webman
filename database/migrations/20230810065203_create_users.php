<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {

        $table = $this->table('users');
        $table->addColumn('name', 'string',
            [
                'comment' => '用户名',
                'limit' => 20
            ]
        );
        $table->addColumn('email', 'string',
            [
                'comment' => '用户邮箱',
                'limit' => 100
            ]
        );
        $table->addColumn('password', 'string',
            [
                'comment' => '用户密码',
                'limit' => 100
            ]
        );
        $table->addColumn('remember_token', 'string',
            [
                'comment' => '用户token',
                'limit' => 100
            ]
        );
        $table->addColumn('created_at', 'timestamp',
            [
                'default' => 'CURRENT_TIMESTAMP'
            ]
        );
        $table->addColumn('updated_at', 'timestamp',
            [
                'default' => 'CURRENT_TIMESTAMP'
            ]
        );
        $table->create();
    }

    public function down()
    {
        $this->table('users')->drop()->save();
    }
}
