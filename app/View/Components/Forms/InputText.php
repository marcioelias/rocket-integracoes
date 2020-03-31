<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class InputText extends Component
{
    public $label;
    public $field;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $field)
    {
        $this->label = $label;
        $this->field = $field;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.form.input-text');
    }
}
