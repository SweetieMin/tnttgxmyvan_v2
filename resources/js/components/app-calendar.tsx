'use client'

import React, { useState } from 'react'
import FullCalendar from '@fullcalendar/react'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin, {
  DateClickArg,
  EventClickArg,
  EventDropArg,
  EventResizeDoneArg,
} from '@fullcalendar/interaction'


interface CalendarEvent {
  id: string
  title: string
  start: string
  end?: string
  backgroundColor?: string
  borderColor?: string
  editable?: boolean
}

interface AppCalendarProps {
  events?: CalendarEvent[]
  onDateClick?: (dateStr: string) => void
  onEventClick?: (eventId: string) => void
  onSelectRange?: (start: string, end: string) => void
  onEventDrop?: (event: { id: string; start: string; end?: string }) => void
  onEventResize?: (event: { id: string; start: string; end?: string }) => void
}

export const AppCalendar: React.FC<AppCalendarProps> = ({
  events = [],
  onDateClick,
  onEventClick,
  onSelectRange,
  onEventDrop,
  onEventResize,
}) => {
  const [currentView, setCurrentView] = useState<'dayGridMonth' | 'timeGridWeek' | 'timeGridDay'>('dayGridMonth')

  return (
    <div className="p-4 bg-card rounded-lg border">
      <FullCalendar
        plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin]}
        initialView={currentView}
        headerToolbar={{
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay',
        }}
        events={events}
        selectable={true}
        editable={true}               // ✅ Cho phép kéo & resize
        droppable={true}              // ✅ Cho phép thả từ ngoài vào
        eventResizableFromStart={true}
        selectMirror={true}
        dayMaxEvents={5}
        height="auto"
        locale="vi"
        buttonText={{
          today: 'Hôm nay',
          month: 'Tháng',
          week: 'Tuần',
          day: 'Ngày',
        }}
        dateClick={(info: DateClickArg) => onDateClick?.(info.dateStr)}
        eventClick={(info: EventClickArg) => onEventClick?.(info.event)}
        select={(info) => onSelectRange?.(info.startStr, info.endStr)}
        eventDrop={(info: EventDropArg) => {
          onEventDrop?.({
            id: info.event.id,
            start: info.event.startStr,
            end: info.event.endStr ?? undefined,
          })
        }}
        eventResize={(info: EventResizeDoneArg) => {
          onEventResize?.({
            id: info.event.id,
            start: info.event.startStr,
            end: info.event.endStr ?? undefined,
          })
        }}
      />
    </div>
  )
}
