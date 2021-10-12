import React from "react";


export const Calendar = ({calendar, month}) => {
    let eventsDates = [];
    if(calendar.events.length !== 0) {
        eventsDates = calendar.events.map((event) => {
            return parseInt(event.event_date.split(' ')[0].split('-').slice(-1)[0])
        })
    }

    return (
        <div className="card">
            <div className="card-panel teal DaysBlockGrid">
                {
                    
                    month.map((day, index) => {
                        const className = eventsDates.indexOf(day) !== -1 ? "DayBlock DayBlockWithEvent" : "DayBlock"
                        return (
                            <div key={index} className={ index === 0 ? "MonthBlock" : className}>
                                <div>
                                    <p>
                                        {day !== 0  ? day : ''}
                                    </p>
                                </div>
                            </div>
                        )
                   })
                    
                }
                
            </div>
            <div className="card-content">
                <span className="card-title activator grey-text text-darken-4">{calendar.calendar.calendar_title}<i className="material-icons right">more_vert</i></span>
            </div>
            <div className="card-reveal">
                <span className="card-title grey-text text-darken-4">{calendar.calendar.calendar_title}<i className="material-icons right">close</i></span>
                <p>Here is some more information about this product that is only revealed once clicked on.</p>
            </div>
      </div>
    );
  }