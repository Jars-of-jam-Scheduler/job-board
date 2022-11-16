<?php

namespace App\Notifications;

use App\Models\JobUser;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobApplication extends Notification implements ShouldQueue
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
        return (new MailMessage)->view(
			'emails.new_job_application',
			[
				'applier_name' => $this->job_application->user->name,
				'job_title' => $this->job_application->job
			]
		)
		->from('thegummybears@example.fr', 'TheGummyBears')
		->subject('Someone applied to one of your jobs!');
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
