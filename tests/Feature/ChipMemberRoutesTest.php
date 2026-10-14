<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

/**
 * 会员管理路由约束（只读路由表，不发 HTTP 请求、不触数据库）：
 * 会员数据归 Java 后端管，admin 只允许 列表/详情/禁用-启用，
 * 绝不暴露 新建/删除 端点。
 */
class ChipMemberRoutesTest extends TestCase
{
    public function test_member_relations_point_to_member_id(): void
    {
        $member = new \App\Models\ChipMember;

        $this->assertSame('member_id', $member->loginLogs()->getForeignKeyName());
        $this->assertSame('member_id', $member->oauthAccounts()->getForeignKeyName());
    }

    /** @return array<string, array<int, string>> uri => methods */
    private function chipMemberRoutes(): array
    {
        $routes = [];
        foreach (app('router')->getRoutes() as $route) {
            $uri = $route->uri();
            if (str_contains($uri, 'chip-member')) {
                $routes[$uri] = $route->methods();
            }
        }

        return $routes;
    }

    public function test_chip_member_resource_is_registered(): void
    {
        $routes = $this->chipMemberRoutes();

        $this->assertNotEmpty($routes, 'chip-member 路由未注册');
    }

    public function test_create_and_destroy_are_not_exposed(): void
    {
        $routes = $this->chipMemberRoutes();

        foreach ($routes as $uri => $methods) {
            $this->assertNotContains('POST', $methods, "不应暴露新建端点: {$uri}");
            $this->assertNotContains('DELETE', $methods, "不应暴露删除端点: {$uri}");
        }
    }

    public function test_status_update_endpoint_exists(): void
    {
        $routes = $this->chipMemberRoutes();

        $hasUpdate = false;
        foreach ($routes as $methods) {
            if (in_array('PUT', $methods, true) || in_array('PATCH', $methods, true)) {
                $hasUpdate = true;
                break;
            }
        }

        $this->assertTrue($hasUpdate, '缺少 update 端点（禁用/启用依赖它）');
    }

    public function test_update_maps_to_member_controller(): void
    {
        foreach (app('router')->getRoutes() as $route) {
            if (! str_contains($route->uri(), 'chip-member')) {
                continue;
            }
            $this->assertStringContainsString(
                'ChipMemberController',
                $route->getActionName(),
                "路由 {$route->uri()} 未指向 ChipMemberController"
            );
        }
    }
}
