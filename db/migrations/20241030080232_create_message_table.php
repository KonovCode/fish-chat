<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMessageTable extends AbstractMigration
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
    public function change(): void
    {
        $table = $this->table('message');
        $table->addColumn('dialog_id', 'integer', ['null' => true, 'signed' => false]);
        $table->addColumn('user_id', 'integer', ['null' => true, 'signed' => false]);
        $table->addColumn('text', 'text', ['limit' => 500, 'null' => true]);
        $table->addColumn('created', 'datetime', ['null' => false]);

        $table->addForeignKey('dialog_id', 'dialog', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION']);
        $table->addForeignKey('user_id', 'phpauth_users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION']);
        $table->create();
    }
}
