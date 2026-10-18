<?php

namespace Tests\Feature;

use Tests\TestCase;

class MenuEntryTest extends TestCase
{
    public function test_menu_entry_page_displays_table_code_form(): void
    {
        $response = $this->get(route('menu.page'));

        $response
            ->assertOk()
            ->assertSee('كود الطاولة')
            ->assertSee('الذهاب إلى القائمة');

        $this->assertSame(6, substr_count($response->getContent(), 'name="code[]"'));
    }

    public function test_table_code_redirects_to_menu_without_hash_and_in_uppercase(): void
    {
        $this->get(route('menu.page', ['code' => '#a4t56g']))
            ->assertRedirect(route('menu.login', ['code' => 'A4T56G']));
    }

    public function test_six_code_boxes_redirect_to_menu_in_uppercase(): void
    {
        $this->get(route('menu.page', ['code' => ['a', '4', 't', '5', '6', 'g']]))
            ->assertRedirect(route('menu.login', ['code' => 'A4T56G']));
    }

    public function test_invalid_table_code_returns_to_form_with_an_error(): void
    {
        $this->get(route('menu.page', ['code' => 'A4T5']))
            ->assertRedirect(route('menu.page'))
            ->assertSessionHasErrors('code');
    }
}
