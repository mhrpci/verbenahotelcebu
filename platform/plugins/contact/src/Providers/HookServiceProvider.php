<?php

namespace Botble\Contact\Providers;

use Html;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Botble\Contact\Repositories\Interfaces\ContactInterface;
use Theme;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @var Collection
     */
    protected $unreadContacts = [];

    /**
     * @throws \Throwable
     */
    public function boot()
    {
        $this->app->booted(function () {
            add_filter(BASE_FILTER_TOP_HEADER_LAYOUT, [$this, 'registerTopHeaderNotification'], 120);
            add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getUnreadCount'], 120, 2);

            if (function_exists('add_shortcode')) {
                add_shortcode('contact-form', trans('plugins/contact::contact.shortcode_name'), trans('plugins/contact::contact.shortcode_description'), [$this, 'form']);
                shortcode()
                    ->setAdminConfig('contact-form', view('plugins/contact::partials.short-code-admin-config')->render());
            }
        });
    }

    /**
     * @param string $options
     * @return string
     *
     * @throws \Throwable
     */
    public function registerTopHeaderNotification($options)
    {
        if (Auth::user()->hasPermission('contacts.edit')) {
            $contacts = $this->setUnreadContacts();

            if ($contacts->count() == 0) {
                return $options;
            }

            return $options . view('plugins/contact::partials.notification', compact('contacts'))->render();
        }

        return $options;
    }

    /**
     * @param int $number
     * @param string $menuId
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getUnreadCount($number, $menuId)
    {
        if ($menuId == 'cms-plugins-contact') {
            $unread = count($this->setUnreadContacts());

            if ($unread > 0) {
                return Html::tag('span', (string)$unread, ['class' => 'badge badge-success'])->toHtml();
            }
        }

        return $number;
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUnreadContacts(): Collection
    {
        if (!$this->unreadContacts) {
            $this->unreadContacts = $this->app->make(ContactInterface::class)
                ->getUnread(['id', 'name', 'email', 'phone', 'created_at']);
        }

        return $this->unreadContacts;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function form($shortcode)
    {
        $view = apply_filters(CONTACT_FORM_TEMPLATE_VIEW, 'plugins/contact::forms.contact');

        if (defined('THEME_OPTIONS_MODULE_SCREEN_NAME')) {
            $this->app->booted(function () {
                Theme::asset()
                    ->usePath(false)
                    ->add('contact-css', asset('vendor/core/plugins/contact/css/contact-public.css'), [], [], '1.0.0');

                Theme::asset()
                    ->container('footer')
                    ->usePath(false)
                    ->add('contact-public-js', asset('vendor/core/plugins/contact/js/contact-public.js'),
                        ['jquery'], [], '1.0.0');
            });
        }

        if ($shortcode->view && view()->exists($shortcode->view)) {
            $view = $shortcode->view;
        }

        return view($view)->render();
    }
}
