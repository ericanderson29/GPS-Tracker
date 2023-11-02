<?php declare(strict_types=1);

namespace App\Domains\MaintenanceItem\Test\Controller;

class Update extends ControllerAbstract
{
    /**
     * @var string
     */
    protected string $route = 'maintenance-item.update';

    /**
     * @var string
     */
    protected string $action = 'update';

    /**
     * @return void
     */
    public function testGetGuestUnauthorizedFail(): void
    {
        $this->getGuestUnauthorizedFail();
    }

    /**
     * @return void
     */
    public function testPostGuestUnauthorizedFail(): void
    {
        $this->postGuestUnauthorizedFail();
    }

    /**
     * @return void
     */
    public function testGetAuthSuccess(): void
    {
        $this->getAuthSuccess();
    }

    /**
     * @return void
     */
    public function testPostAuthSuccess(): void
    {
        $this->postAuthSuccess();
    }

    /**
     * @return void
     */
    public function testPostAuthUpdateSuccess(): void
    {
        $this->postAuthUpdateSuccess();
    }

    /**
     * @return void
     */
    public function testGetAuthUpdateAdminSuccess(): void
    {
        $this->getAuthUpdateAdminSuccess();
    }

    /**
     * @return void
     */
    public function testGetAuthUpdateAdminModeSuccess(): void
    {
        $this->getAuthUpdateAdminModeSuccess(true, false);
    }

    /**
     * @return void
     */
    public function testPostAuthUpdateAdminModeSuccess(): void
    {
        $this->postAuthUpdateAdminModeSuccess(true, false);
    }

    /**
     * @return string
     */
    protected function routeToController(): string
    {
        return $this->routeFactoryCreateModel();
    }
}
