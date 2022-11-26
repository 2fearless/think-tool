<?php


namespace Fearless\ThinkTool;


class CommonScaffold
{
    /**
     * Replace spaces.
     *
     * @param string $stub
     *
     * @return mixed
     */
    public function replaceSpace($stub)
    {
        return str_replace(["\n\n\n", "\n    \n"], ["\n\n", ''], $stub);
    }
}
