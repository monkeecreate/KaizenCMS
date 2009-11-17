BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
PRODID:-//{$domain}//calendar
X-LOTUS-CHARSET:UTF-8
X-WR-CALNAME:Calendar
X-WR-CALDESC:Calendar
X-WR-TIMEZONE:US/Central
METHOD:PUBLISH

BEGIN:VTIMEZONE
TZID:US/Central
BEGIN:DAYLIGHT
TZOFFSETFROM:-0600
TZOFFSETTO:-0500
DTSTART:20070311T020000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU
TZNAME:CDT
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:-0500
TZOFFSETTO:-0600
DTSTART:20071104T020000
RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU
TZNAME:CST
END:STANDARD
END:VTIMEZONE

{foreach from=$aEvents item=aEvent}
BEGIN:VEVENT
CLASS:PUBLIC
SUMMARY:{$aEvent.title|htmlspecialchars|stripslashes}
URL:http://{$domain}/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/
{if $aEvent.allday == 1}
DTSTART;VALUE=DATE:{$aEvent.datetime_start|date_format:"%Y%m%d"}
DTEND;VALUE=DATE:{$aEvent.datetime_end|date_format:"%Y%m%dT240000"}
{else}
DTSTART:{$aEvent.datetime_start|date_format:"%Y%m%dT%H%M%S"}
DTEND:{$aEvent.datetime_end|date_format:"%Y%m%dT%H%M%S"}
{/if}
UID:{$aEvent.id}
END:VEVENT

{/foreach}
END:VCALENDAR