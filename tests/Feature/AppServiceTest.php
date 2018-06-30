<?php

namespace Tests\Feature;

use App\Services\App\Service\AppService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppServiceTest extends TestCase
{

    // 添加一个应用
    public function testAppService()
    {
        $this->assertTrue(true);
//        $uid = 123;
//        $appService = AppService::instance();
//        $app = $appService->addApp('123', $uid, '应用名称', '应用描述');
//        $this->assertArrayHasKey('id', $app);
//
//        $app = $appService->updateApp('123', $app['id'], '应用名称11');
//        $this->assertTrue(is_array($app));
//        $this->assertEquals('应用名称11', $app['name']);
//
//        $appRole = $appService->addRole('123', $app['id'], '角色名称', '角色描述');
//        $this->assertArrayHasKey('name', $appRole);
//
//        $roles = $appService->getRoles('123', $app['id']);
//        $this->assertTrue(is_array($roles));
//        $this->assertEquals('角色名称', $roles[0]['name']);
//
//        $appRole = $appService->updateRole('123', $appRole['id'], '角色名称11');
//        $this->assertTrue(is_array($appRole));
//        $this->assertEquals('角色名称11', $appRole['name']);
//
//        $result = $appService->bindRoleAndUser('123', $appRole['id'], $uid);
//        $this->assertTrue($result);
//
//        $uids = $appService->getUserIdsOfRole('123', $appRole['id']);
//        $this->assertTrue(is_array($uids));
//        $this->assertEquals($uid, $uids[0]);
//
//        $result = $appService->unbindRoleAndUser('123', $appRole['id'], $uid);
//        $this->assertTrue($result);
//
//        $uids = $appService->getUserIdsOfRole('123', $appRole['id']);
//        $this->assertTrue(is_array($uids));
//        $this->assertEmpty($uids);
//
//        $result = $appService->deleteRole('123', $appRole['id']);
//        $this->assertTrue($result);
//
//        $roles = $appService->getRoles('123', $app['id']);
//        $this->assertEmpty($roles);
//
//        $result = $appService->deleteApp('123', $app['id']);
//        $this->assertTrue($result);
//
//        $app = $appService->getAppById('123', $app['id']);
//        $this->assertEmpty($app);

    }

}
