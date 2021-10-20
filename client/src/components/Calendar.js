import React, {useContext} from "react";
import { useHistory } from 'react-router-dom';
import {AuthContext} from '../context/AuthContext'
import { useHttp } from '../hooks/http.hook';


export const Calendar = ({calendar, month}) => {
    const history = useHistory();
    const {token} = useContext(AuthContext);
    const {request} = useHttp();

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

    const RemoveCalendar = async(event) => {
        event.preventDefault()
        try {
            await request('/api/DeleteCalendar/' + calendar.calendar.id, 'DELETE', null, {'Authorization': token})
        }
        catch (e) {}
        history.push(`/`)
    }

    return (
        <div className="card">
            <div className="card-panel teal DaysBlockGrid" onClick={moveToCalendarPage}>
                {
                    
                    month.map((day, index) => {
                        const className = eventsDates.indexOf(day) !== -1 ? "DayBlock DayBlockWithEvent" : "DayBlock"
                        return (
                            <div key={index} className={ index === 0 ? "MonthBlock white-text" : className}>
                                <div>
                                    <p className="white-text">
                                        {day !== 0  ? day.indexOf('-') !== -1 ? parseInt(day.split('-').slice(-1)[0]) : day : ''}
                                    </p>
                                </div>
                            </div>
                        )
                   })
                    
                }
                
            </div>
            <div className="card-content center">
                <span className="card-title grey-text text-darken-4">{calendar.calendar.calendar_title}</span>
                <button className="btn btn-large waves-effect waves-light red" onClick={RemoveCalendar}>Remove Calendar</button>
            </div>
      </div>
    );
  }