# Workshop Attendance

## Summary

Restricts Moodle activities and sections according to the learner's booking or attendance status in Workshop Scheduler.

## Description

Workshop Attendance is a Moodle availability condition ("Restrict access" condition). It lets teachers make an activity, resource or course section available only when a learner has the required booking status in a selected Workshop Scheduler (`local_workshops`) workshop.

A typical use is to unlock a post-workshop survey or assessment only after the learner has been marked **Attended**. It can also be used the other way round (for example, show catch-up material to learners marked **No Show**), or negated with Moodle's "must not" option.

## Requirements

- Moodle 4.0 or later (`$plugin->requires = 2022041900`). Tested on Moodle 4.5.
- Workshop Scheduler (`local_workshops`) version 2026072400 (v1.0.32) or later. Moodle will not install this plugin without it.
- Restrict access enabled on the site (Site administration > Advanced features > Enable restricted access).

## Installation

1. Install Workshop Scheduler (`local_workshops`) first.
2. Install this plugin in either of these ways:
   - Site administration > Plugins > Install plugins, and upload the ZIP; or
   - Unzip it so the folder is at `availability/condition/workshopattendance` in your Moodle directory.
3. Visit Site administration > Notifications to complete the installation.

There are no settings to configure.

## Usage

1. Edit an activity, resource or section and open **Restrict access**.
2. Click **Add restriction...** and choose **Workshop attendance**.
3. Select the **Workshop**. Workshops linked to the current course are listed first, under "Workshops linked to this course"; all other workshops on the site follow under "Other workshops". Each entry shows the workshop code, name and workshop status.
4. Select the **Booking status** that grants access: Booked, Confirmed, Waitlisted, Attended, Cancelled or No Show. Attended is selected by default.
5. Save. A workshop must be selected before the form can be saved.

Combine it with other conditions (dates, grades, completion) using Moodle's usual "must / must not" and "all / any" options.

## Features

- Grants access when the learner has a booking in the selected workshop with exactly the selected booking status.
- Supports all six Workshop Scheduler booking statuses.
- Supports negation ("must not have a booking status of ...").
- Learners who cannot access the item see a message naming the required status and the workshop (code and name).
- Workshop list in the editor groups the current course's workshops first.
- Stores no personal data (Privacy API null provider).

## How it works

The condition stores the selected workshop ID and booking status with the activity or section. When Moodle checks access, it looks in `local_workshop_participants` for a booking with that workshop ID, the learner's Moodle user ID and that booking status.

Points to note:

- Bookings are matched by the Moodle account linked to the booking. A booking that has no linked Moodle account does not meet the condition.
- The status must match exactly. For example, a condition requiring Attended is not met by Confirmed.
- If no workshop is set, or the selected workshop has been deleted, the condition is not met and the workshop is shown as "(Unknown workshop)".
- The condition stores the workshop's database ID. Workshops belong to the site, not the course, so after a course backup is restored on a different site the condition may refer to a workshop that does not exist there; edit the restriction to select the right workshop.
- The workshop list in the editor shows every workshop on the site to anyone who can edit the activity's restrictions.

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## Licence

GNU GPL v3 or later. See [LICENSE](LICENSE).
