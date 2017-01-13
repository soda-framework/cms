<?php

// This line is necessary to override the Router implementation at app/Http/Kernel.php
class_alias('\Illuminate\Routing\Router', Router::class);
