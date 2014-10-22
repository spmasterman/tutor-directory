<?php

namespace Fitch\FrontEndBundle\Twig\Extension;

use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;

class TimeAgoExtension extends \Twig_Extension
{
    protected $translator;

    /**
     * Constructor method
     *
     * @param IdentityTranslator $translator
     */
    public function __construct($translator)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('distance_of_time_in_words', array($this, 'distanceOfTimeInWordsFilter')),
            new \Twig_SimpleFilter('time_ago_in_words', array($this, 'timeAgoInWordsFilter')),
        );
    }

    /**
     * Like distance_of_time_in_words, but where to_time is fixed to timestamp()
     *
     * @param $fromTime String or DateTime
     * @param bool $includeSeconds
     * @param bool $includeMonths
     *
     * @return mixed
     */
    public function timeAgoInWordsFilter($fromTime, $includeSeconds = false, $includeMonths = false)
    {
        return $this->distanceOfTimeInWordsFilter($fromTime, new \DateTime('now'), $includeSeconds, $includeMonths);
    }

    /**
     * Reports the approximate distance in time between two times given in seconds
     * or in a valid ISO string like.
     * For example, if the distance is 47 minutes, it'll return
     * "about 1 hour". See the source for the complete wording list.
     *
     * Integers are interpreted as seconds. So, by example to check the distance of time between
     * a created user an it's last login:
     * {{ user.createdAt|distance_of_time_in_words(user.lastLoginAt) }} returns "less than a minute".
     *
     * Set include_seconds to true if you want more detailed approximations if distance < 1 minute
     * Set include_months to true if you want approximations in months if days > 30
     *
     * @param $fromTime String or DateTime
     * @param $toTime String or DateTime
     * @param bool $includeSeconds
     * @param bool $includeMonths
     *
     * @return mixed
     */
    public function distanceOfTimeInWordsFilter(
        $fromTime,
        $toTime = null,
        $includeSeconds = false,
        $includeMonths = false
    ) {
        $datetimeTransformer = new DateTimeToStringTransformer(null, null, 'Y-m-d H:i:s');
        $timestampTransformer = new DateTimeToTimestampTransformer();

        # Transforming to Timestamp
        $fromTime = $this->transformToTimestamp($fromTime, $datetimeTransformer, $timestampTransformer);

        $toTime = empty($toTime) ? new \DateTime('now') : $toTime;
        $toTime = $this->transformToTimestamp($toTime, $datetimeTransformer, $timestampTransformer);

        $minutes = round((abs($toTime - $fromTime))/60);
        $seconds = round(abs($toTime - $fromTime));

        if ($toTime > $fromTime) {
            return $this->inThePast($includeSeconds, $includeMonths, $minutes, $seconds);
        } elseif ($toTime < $fromTime) {
            return $this->inTheFuture($includeSeconds, $includeMonths, $minutes, $seconds);
        } else {
            return 'now';
        }
    }

    public function inTheFuture($includeSeconds, $includeMonths, $minutes, $seconds)
    {
        if ($minutes <= 1) {
            return $includeSeconds
                ? $this->subFutureMinuteWithSeconds($seconds)
                : $this->subFutureMinute($minutes);
        } elseif ($minutes <= 45) {
            return $this->translator->transchoice(
                'in %minutes minutes',
                $minutes,
                array('%minutes' => $minutes)
            );
        } elseif ($minutes <= 90) {
            return $this->translator->trans('in about 1 hour');
        } elseif ($minutes <= 1440) {
            return $this->translator->transchoice(
                'in about %hours hours',
                round($minutes / 60),
                array('%hours' => round($minutes / 60))
            );
        } else {
            return $this->halfFutureDayOrLonger($minutes, $includeMonths);
        }
    }

    /**
     * @param $includeSeconds
     * @param $includeMonths
     * @param $minutes
     * @param $seconds
     * @return string
     */
    public function inThePast($includeSeconds, $includeMonths, $minutes, $seconds)
    {
        if ($minutes <= 1) {
            return $includeSeconds
                ? $this->subPastMinuteWithSeconds($seconds)
                : $this->subPastMinute($minutes);
        } elseif ($minutes <= 45) {
            return $this->translator->transchoice(
                '%minutes minutes ago',
                $minutes,
                array('%minutes' => $minutes)
            );
        } elseif ($minutes <= 90) {
            return $this->translator->trans('about 1 hour ago');
        } elseif ($minutes <= 1440) {
            return $this->translator->transchoice(
                'about %hours hours ago',
                round($minutes / 60),
                array('%hours' => round($minutes / 60))
            );
        } else {
            return $this->halfPastDayOrLonger($minutes, $includeMonths);
        }
    }

    /**
     * @param $timeIn
     * @param DateTimeToStringTransformer $datetimeTransformer
     * @param DateTimeToTimestampTransformer $timestampTransformer
     * @return mixed
     */
    private function transformToTimestamp($timeIn, $datetimeTransformer, $timestampTransformer)
    {
        if (!($timeIn instanceof \DateTime) && !is_numeric($timeIn)) {
            $timeIn = $datetimeTransformer->reverseTransform($timeIn);
            $timeIn = $timestampTransformer->transform($timeIn);

            return $timeIn;
        } elseif ($timeIn instanceof \DateTime) {
            $timeIn = $timestampTransformer->transform($timeIn);

            return $timeIn;
        }

        return $timeIn;
    }

    /**
     * @param $seconds
     * @return string
     */
    private function subPastMinuteWithSeconds($seconds)
    {
        return $this->subMinuteWithSeconds($seconds) . ' ago';
    }

    /**
     * @param $seconds
     * @return string
     */
    private function subFutureMinuteWithSeconds($seconds)
    {
      return 'in ' . $this->subMinuteWithSeconds($seconds);
    }

    /**
     * @param $seconds
     * @return string
     */
    private function subMinuteWithSeconds($seconds)
    {
        if ($seconds < 5) {
            return $this->translator->trans('less than %seconds seconds', array('%seconds' => 5));
        } elseif ($seconds < 10) {
            return $this->translator->trans('less than %seconds seconds', array('%seconds' => 10));
        } elseif ($seconds < 20) {
            return $this->translator->trans('less than %seconds seconds', array('%seconds' => 20));
        } elseif ($seconds < 40) {
            return $this->translator->trans('half a minute');
        } elseif ($seconds < 60) {
            return $this->translator->trans('less than a minute');
        } else {
            return $this->translator->trans('in 1 minute');
        }
    }

    /**
     * @param $minutes
     * @return string
     */
    protected function subPastMinute($minutes)
    {
        return ($minutes === 0)
            ? $this->translator->trans('less than a minute ago', array())
            : $this->translator->trans('1 minute ago', array());
    }

    /**
     * @param $minutes
     * @return string
     */
    protected function subFutureMinute($minutes)
    {
        return ($minutes === 0)
            ? $this->translator->trans('in less than a minute', array())
            : $this->translator->trans('in 1 minute', array());
    }

    private function halfFutureDayOrLonger($minutes, $includeMonths)
    {
        return 'in ' . $this->halfDayOrLonger($minutes, $includeMonths);
    }

    private function halfPastDayOrLonger($minutes, $includeMonths)
    {
        return $this->halfDayOrLonger($minutes, $includeMonths) . ' ago';
    }

    /**
     * @param $minutes
     * @param $includeMonths
     * @return string
     */
    private function halfDayOrLonger($minutes, $includeMonths)
    {
        if ($minutes <= 2880) {
            return $this->translator->trans('1 day ago');
        } else {
            $days = round($minutes/1440);
            if (!$includeMonths || $days <= 30) {
                return $this->translator->trans('%days days ago', array('%days' => round($days)));
            } else {
                $months = round($days/30);
                return $this->translator->transchoice(
                    '{1} 1 month ago |]1,Inf[ %months months ago',
                    $months,
                    array('%months' => $months)
                );
            }
        }
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'time_ago_extension';
    }
}
