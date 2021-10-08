import React from "react";

export const Calendar = ({calendar, month}) => {
    return (
        <div className="card">
            <div className="card-panel teal DaysBlockGrid">
                {
                   month.map((day, index) => {
                        return (
                            <div key={index} className="DayBlock">
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
                <span className="card-title activator grey-text text-darken-4">{calendar.calendar_title}<i className="material-icons right">more_vert</i></span>
            </div>
            <div className="card-reveal">
                <span className="card-title grey-text text-darken-4">{calendar.calendar_title}<i className="material-icons right">close</i></span>
                <p>Here is some more information about this product that is only revealed once clicked on.</p>
            </div>
      </div>
    );
  }