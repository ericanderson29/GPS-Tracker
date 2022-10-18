<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Shared\Migration\MigrationAbstract;

return new class extends MigrationAbstract
{
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
        return Schema::hasColumn('position', 'city_id');
    }

    /**
     * @return void
     */
    protected function tables()
    {
        Schema::table('position', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id')->nullable();
        });
    }

    /**
     * @return void
     */
    public function keys()
    {
        Schema::table('position', function (Blueprint $table) {
            $this->foreignOnDeleteSetNull($table, 'city');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('position', function (Blueprint $table) {
            $table->dropForeign('position_city_fk');
            $table->dropColumn('city_id');
        });
    }
};
