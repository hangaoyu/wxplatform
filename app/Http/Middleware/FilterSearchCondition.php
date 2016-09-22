<?php

namespace App\Http\Middleware;

use Closure;

class FilterSearchCondition
{
    protected $betweenFields = ['created_at', 'updated_at'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $array = [];

        $params = $request->all();
        //var_dump($params);
        foreach( $this->betweenFields as $betweenField ){
            $minKey = $betweenField."Min";
            $maxKey = $betweenField."Max";
            $min = isset( $params[$minKey] ) ? $params[$minKey] : false;
            $max = isset( $params[$maxKey] ) ? $params[$maxKey] : false;
            unset($params[$minKey]);
            unset($params[$maxKey]);
            if( $min && $max ){
                $params[$betweenField] = $min . ' - ' . $max;
            }
        }
        //var_dump($params);
        foreach ($params as $field => $value) {
            if (( ! empty($value) || $value === '0')) {
                if ($field === 'page') {
                    $array['page'] = $value;
                    continue;
                }
                if (in_array($field, $this->betweenFields)) {
                    $array['where'][] = [$field, 'between', $this->formatBetweentField($value)];
                } else {
                    $array['where'][] = [$field, 'like', '%' . $value . '%'];
                }
            }
        }
        //var_dump($array);
        $request->flash();
        $request->replace($array);

        return $next($request);
    }

    /**
     * format string time to array time
     *
     * @param  mixed $value
     *
     * @return array
     */
    public function formatBetweentField($value)
    {
        $string = str_replace('/', '-', $value);
        $array = explode(' - ', $string);

        return $array;
    }
}
