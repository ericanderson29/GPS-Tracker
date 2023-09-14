<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\SharedApp\Migration\MigrationAbstract;

return new class extends MigrationAbstract {
    /**
     * @return void
     */
    public function up()
    {
        if ($this->upMigrated()) {
            return;
        }

        $this->tables();
        $this->keys();
        $this->upFinish();
    }

    /**
     * @return bool
     */
    protected function upMigrated(): bool
    {
        return Schema::hasTable('maintenance');
    }

    /**
     * @return void
     */
    protected function tables()
    {
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->string('workshop')->default('');

            $table->text('description');

            $table->dateTime('date_at');

            $table->unsignedDecimal('amount', 10, 2)->default(0);

            $table->unsignedDecimal('distance', 10, 2)->default(0);
            $table->unsignedDecimal('distance_next', 10, 2)->default(0);

            $this->timestamps($table);

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vehicle_id');
        });
    }

    /**
     * Set the foreign keys.
     *
     * @return void
     */
    protected function keys()
    {
        Schema::table('maintenance', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'user');
            $this->foreignOnDeleteCascade($table, 'vehicle');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::drop('maintenance');
    }
};
