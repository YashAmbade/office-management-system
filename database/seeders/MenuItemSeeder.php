<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Department;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $gmbDeptId = Department::where('code', 'GMB')->value('id');

        $items = [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'ph-squares-four', 'route_name' => 'dashboard', 'section' => 'Main', 'sort_order' => 1],

            ['key' => 'employees', 'label' => 'Employees', 'icon' => 'ph-users-three', 'route_name' => 'users.index', 'section' => 'People', 'sort_order' => 1],
            ['key' => 'departments', 'label' => 'Departments', 'icon' => 'ph-buildings', 'route_name' => 'departments.index', 'section' => 'People', 'sort_order' => 2],

            ['key' => 'tasks', 'label' => 'Tasks', 'icon' => 'ph-list-checks', 'route_name' => 'tasks.index', 'section' => 'Workspace', 'sort_order' => 1],
            ['key' => 'clients', 'label' => 'SMO Clients', 'icon' => 'ph-briefcase', 'route_name' => 'clients.index', 'section' => 'Workspace', 'sort_order' => 2],
            ['key' => 'work_log', 'label' => 'Work Log', 'icon' => 'ph-notepad', 'route_name' => 'work-log.index', 'section' => 'Workspace', 'sort_order' => 3],
            ['key' => 'work_log_clients', 'label' => 'Work Log Clients', 'icon' => 'ph-briefcase', 'route_name' => 'work-log.clients', 'section' => 'Workspace', 'sort_order' => 4],
            ['key' => 'work_log_report', 'label' => 'Work Log Report', 'icon' => 'ph-chart-bar', 'route_name' => 'work-log.report', 'section' => 'Workspace', 'sort_order' => 5],

            // GMB parent + children — restricted to GMB department
            ['key' => 'gmb', 'label' => 'GMB / SEO', 'icon' => 'ph-check-square', 'section' => 'GMB / SEO', 'restricted_department_id' => $gmbDeptId, 'sort_order' => 1],
            ['key' => 'gmb_checklist', 'label' => 'Checklist', 'icon' => 'ph-check-square', 'route_name' => 'gmb.checklist', 'restricted_department_id' => $gmbDeptId, 'parent_key' => 'gmb', 'sort_order' => 1],
            ['key' => 'gmb_clients', 'label' => 'Clients', 'icon' => 'ph-briefcase', 'route_name' => 'gmb.clients', 'restricted_department_id' => $gmbDeptId, 'parent_key' => 'gmb', 'sort_order' => 2],
            ['key' => 'gmb_categories', 'label' => 'Manage Categories', 'icon' => 'ph-list-bullets', 'route_name' => 'gmb.categories', 'restricted_department_id' => $gmbDeptId, 'parent_key' => 'gmb', 'sort_order' => 3],

            ['key' => 'reports', 'label' => 'SMO Employee', 'icon' => 'ph-chart-bar', 'route_name' => 'reports.employees', 'section' => 'Reports', 'sort_order' => 1],

            ['key' => 'settings', 'label' => 'Settings', 'icon' => 'ph-gear', 'route_name' => 'settings.index', 'section' => 'System', 'sort_order' => 1],
        ];

        $keyToId = [];
        foreach ($items as $data) {
            $parentKey = $data['parent_key'] ?? null;
            unset($data['parent_key']);
            $data['parent_id'] = $parentKey ? ($keyToId[$parentKey] ?? null) : null;

            $item = MenuItem::updateOrCreate(['key' => $data['key']], $data);
            $keyToId[$data['key']] = $item->id;
        }
    }
}
