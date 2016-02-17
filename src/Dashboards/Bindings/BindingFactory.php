<?php

namespace Khill\Lavacharts\Dashboards\Bindings;

use \Khill\Lavacharts\Dashboards\ChartWrapper;
use \Khill\Lavacharts\Dashboards\ControlWrapper;
use \Khill\Lavacharts\Exceptions\InvalidBindings;

/**
 * BindingFactory Class
 *
 * Creates new bindings for dashboards.
 *
 * @package    Khill\Lavacharts
 * @subpackage Dashboards\Bindings
 * @since      3.0.0
 * @author     Kevin Hill <kevinkhill@gmail.com>
 * @copyright  (c) 2015, KHill Designs
 * @link       http://github.com/kevinkhill/lavacharts GitHub Repository Page
 * @link       http://lavacharts.com                   Official Docs Site
 * @license    http://opensource.org/licenses/MIT MIT
 */
class BindingFactory
{
    use \Khill\Lavacharts\Traits\ArrayValuesTestTrait;

    /**
     * Create a new Binding for the dashboard.
     *
     * @param  mixed $controlWraps One or array of many ControlWrappers
     * @param  mixed $chartWraps   One or array of many ChartWrappers
     * @throws \Khill\Lavacharts\Exceptions\InvalidBindings
     * @return \Khill\Lavacharts\Dashboards\Bindings\Binding
     */
    public function create($controlWraps, $chartWraps)
    {
        $chartWrapCheck   = $this->arrayValuesTest($chartWraps, 'class', 'ChartWrapper');
        $controlWrapCheck = $this->arrayValuesTest($controlWraps, 'class', 'ControlWrapper');

        if ($controlWraps instanceof ControlWrapper && $chartWraps instanceof ChartWrapper) {
            return new OneToOne($controlWraps, $chartWraps);
        }

        if ($controlWraps instanceof ControlWrapper && $chartWrapCheck) {
            return new OneToMany($controlWraps, $chartWraps);
        }

        if ($controlWrapCheck && $chartWraps instanceof ChartWrapper) {
            return new ManyToOne($controlWraps, $chartWraps);
        }

        if ($controlWrapCheck && $chartWrapCheck) {
            return new ManyToMany($controlWraps, $chartWraps);
        }

        throw new InvalidBindings;
    }

    public function createFromArray($bindings)
    {
        if (is_array($bindings) === false) {
            throw new InvalidBindings;
        }

        return array_map(function ($binding) {
            return self::create($binding[0], $binding[1]);
        }, $bindings);
    }
}