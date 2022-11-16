<?php

namespace App\Notifications;

use App\Models\{User, JobUser};

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefusedJobApplication extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private JobUser $job_application)
    {
        //
    }

	public function getJobApplication() : Jobuser
	{
		return $this->job_application;
	}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
		$job = $this->job_application->job;
        return (new MailMessage)->view(
			'emails.refused_job_application',
			[
				'firm_name' => $job->firm->name,
				'job_title' => $job->title
			]
		)
		->from('thegummybears@example.fr', 'TheGummyBears')
		->subject('Your Job Application has been refused');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
