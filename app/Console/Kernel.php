<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
use Mail;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        return;
        $schedule->call(function() {
            $dtNow = new \DateTime();
            $dtTomorrow = new \DateTime($dtNow->format('Y/m/d'));
            $dtTomorrow->add(new \DateInterval('P1D'));
            
            $list_course_class = DB::table('course_class')
                    ->select([
                        'course_class.id',
                        'course.code AS course_code',
                        'course.name_cht AS course_name_cht',
                        'course.name_eng AS course_name_eng',
                        DB::raw("DATE_FORMAT(`course_class`.`date_start`, '%Y/%m/%d') AS `date_start`"),
                        DB::raw("DATE_FORMAT(`course_class`.`date_end`, '%Y/%m/%d') AS `date_end`"),
                        'course_class_locale.name AS course_class_locale_name',
                        'course_class.class_way',
                        'course_class.class_time',
                        'course.class_time AS course_class_time',
                    ])
                    ->leftJoin('course_class_locale', 'course_class.course_class_locale_id', '=', 'course_class_locale.id')
                    ->leftJoin('course', 'course_class.course_id', '=', 'course.id')
                    ->where('course_class.enable', 1)
                    ->where('course_class.status', 1)
                    ->where('course_class.date_start', $dtTomorrow->format('Y/m/d'))
                    ->get();
            
            foreach ($list_course_class as $k => $v) {
                $list_apply_item = DB::table('apply_item')
                        ->select([
                            'member.email AS member_email',
                            'member.name_cht AS member_name_cht',
                        ])
                        ->leftJoin('member', 'apply_item.member_id', '=', 'member.id')
                        ->where('apply_item.last_course_class_id', $v['id'])
                        ->get();
                
                if (count($list_apply_item) > 0) {
                    try {
                        Mail::send('emails.course_start', [
                            'subject' => trans('email.course_start.subject'),
                            'data_course_class' => $v,
                                ], function ($m) use ($list_apply_item) {
                            foreach ($list_apply_item as $kk => $vv) {
                                $m->bcc($vv['member_email'], $vv['member_name_cht']);
                            }
                            $m->subject(trans('email.course_start.subject'));
                        });
                    } catch (\Exception $ex) {
                        \Log::error('send email "course_start" error: ' . $ex->getMessage());
                    }
                }
            }
        })->dailyAt('01:00');
    }

}
