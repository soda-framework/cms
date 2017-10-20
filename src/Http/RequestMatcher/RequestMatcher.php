<?php

namespace Soda\Cms\Http\RequestMatcher;

use Illuminate\Http\Request;
use Soda\Cms\Http\RequestMatcher\Matchers\MatcherInterface;
use Soda\Cms\Http\RequestMatcher\Matchers\SluggedContentMatcher;
use Soda\Cms\Database\Repositories\Contracts\ApplicationRepositoryInterface;

class RequestMatcher
{
    protected $application;

    protected $matchers = [
        SluggedContentMatcher::class,
    ];

    public function __construct(ApplicationRepositoryInterface $application)
    {
        $this->application = $application;
    }

    /**
     * Registers an array of new matchers.
     *
     * @param $matchers
     */
    public function registerMatchers($matchers)
    {
        foreach ($matchers as $matcher) {
            $this->registerMatcher($matcher);
        }
    }

    /**
     * Registers a new matcher.
     *
     * @param      $matcher
     *
     * @return $this
     */
    public function registerMatcher($matcher)
    {
        if (! in_array($matcher, $this->matchers) && is_a($matcher, MatcherInterface::class, true)) {
            $this->matchers[] = $matcher;
        }

        return $this;
    }

    /**
     * Returns a list of matchers that have been registered.
     *
     * @return array
     */
    public function getRegisteredMatchers()
    {
        return $this->matchers;
    }

    /**
     * Matches a url to an ApplicationUrl model and it's related Application model.
     *
     * @param $url
     *
     * @return mixed
     */
    public function matchApplication($url)
    {
        return $this->application->findByUrl($url);
    }

    /**
     * Loads a page by it's slug.
     *
     * @param Request $request
     */
    public function match(Request $request)
    {
        foreach ($this->matchers as $matcher) {
            $matcherInstance = app($matcher);
            if ($matcherInstance->matches($request->getPathInfo())) {
                return $matcherInstance->render($request);
            }
        }

        return $this->handlePageNotFound();
    }

    /**
     * Handles not found errors.
     */
    public function handlePageNotFound()
    {
        abort(404, '404 Page not found');
    }

    /**
     * Handles not found errors.
     */
    public function handleApplicationNotFound()
    {
        abort(404, 'No Application Found at URL');
    }
}
