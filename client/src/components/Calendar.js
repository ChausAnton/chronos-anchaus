import React from "react";
import { useHistory } from 'react-router-dom';


export const Calendar = ({calendar, month}) => {
    const history = useHistory();
    
    let eventsDates = [];
    if(calendar.events.length !== 0) {
        eventsDates = calendar.events.map((event) => {
            return event.event_date.split(' ')[0]
        })
    }
    
    const moveToCalendarPage = (event) => {
        event.preventDefault();
        history.push(`/calendarpage/${calendar.calendar.id}`)
    }

    return (
        <div className="card">
            <div className="card-panel teal DaysBlockGrid" onClick={moveToCalendarPage}>
                {
                    
                    month.map((day, index) => {
                        const className = eventsDates.indexOf(day) !== -1 ? "DayBlock DayBlockWithEvent" : "DayBlock"
                        return (
                            <div key={index} className={ index === 0 ? "MonthBlock" : className}>
                                <div>
                                    <p>
                                        {day !== 0  ? day.indexOf('-') !== -1 ? parseInt(day.split('-').slice(-1)[0]) : day : ''}
                                    </p>
                                </div>
                            </div>
                        )
                   })
                    
                }
                
            </div>
            <div className="card-content center">
                <span className="card-title activator grey-text text-darken-4">{calendar.calendar.calendar_title}</span>
            </div>
      </div>
    );
  }