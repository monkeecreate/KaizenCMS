BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
PRODID:-//{$domain}//calendar
X-LOTUS-CHARSET:UTF-8
X-WR-CALNAME:Calendar
X-WR-CALDESC:Calendar
X-WR-TIMEZONE:{date("e")}
METHOD:PUBLISH

BEGIN:VTIMEZONE
TZID:{date("e")}
BEGIN:DAYLIGHT
{if date("I") == 1}
TZOFFSETFROM:{str_replace(array("-","+"), array("-0","+0"), (date("O") - 100))}
TZOFFSETTO:{date("O")}
{else}
TZOFFSETFROM:{date("O")}
TZOFFSETTO:{str_replace(array("-","+"), array("-0","+0"), (date("O") + 100))}
{/if}
DTSTART:20070311T020000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU
TZNAME:{date("T")}
END:DAYLIGHT
BEGIN:STANDARD
{if date("I") == 1}
TZOFFSETFROM:{date("O")}
TZOFFSETTO:{str_replace(array("-","+"), array("-0","+0"), (date("O") - 100))}
{else}
TZOFFSETFROM:{str_replace(array("-","+"), array("-0","+0"), (date("O") + 100))}
TZOFFSETTO:{date("O")}
{/if}
DTSTART:20071104T020000
RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU
TZNAME:{date("T")}
END:STANDARD
END:VTIMEZONE

BEGIN:VEVENT
CLASS:PUBLIC
SUMMARY:{$aEvent.title}
URL:http://{$domain}/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/
{if $aEvent.allday == 1}
DTSTART;VALUE=DATE:{$aEvent.datetime_start|formatDate:"Ymd"}
DTEND;VALUE=DATE:{$aEvent.datetime_end|formatDate:"Ymd\T240000"}
{else}
DTSTART:{$aEvent.datetime_start|formatDate:"Ymd\THis"}
DTEND:{$aEvent.datetime_end|formatDate:"Ymd\THis"}
{/if}
UID:{$aEvent.id}
END:VEVENT

END:VCALENDAR