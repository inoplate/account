<?php namespace Inoplate\Account\Widgets;

use Auth;
use Inoplate\Widget\BaseWidget;

class Usercard extends BaseWidget
{
    /**
     * @var integer
     */
    protected $order = 1;

    /**
     * @var string
     */
    protected $view = 'inoplate-account::widgets.user-card';

    /**
     * @inherit_docs
     */
    public function options()
    {
        return [
            'user' => Auth::user()
        ];
    }
}