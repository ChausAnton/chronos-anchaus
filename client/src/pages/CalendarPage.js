import React, { useCallback, useContext, useEffect, useState } from 'react';
import { useHttp } from '../hooks/http.hook';
import {AuthContext} from '../context/AuthContext'
import { Loader } from '../components/Loader';
import { useParams } from "react-router-dom";
import { FiArrowRight, FiArrowLeft} from "react-icons/fi";
import { CreateEvent } from '../components/CreateEvent';

export const CalendarPage = () => {
    const [calendar, setCalendar] = useState();
    const {loading, request} = useHttp();
    const {token} = useContext(AuthContext);
    const [month, setMonth] = useState();
    const [eventsDates, setEventsDates] = useState();
    const [countMonth, setCountMonth] = useState(new Date().getMonth());
    const {id} = useParams();

    const [creteEvetn, setCreteEvetn] = useState(false);
    const [eventStart, setEventStart] = useState(false);


    const NextMonth = (event) => {
        event.preventDefault();
        setCountMonth(countMonth + 1)
    }

    const PrevMonth = (event) => {
        event.preventDefault();
        setCountMonth(countMonth - 1)

    }

    useEffect(()=>{
        const date = new Date();
        date.setMonth(countMonth)
        const nextDays = new Date(date)
        nextDays.setDate(1);
        let curMonth = date.getMonth()
        const tempMonth = [];
        let count = -(date.getDate() -  1);

        tempMonth.push()
        tempMonth.push(date.getFullYear() + '. ' + date.toLocaleString('default', { month: 'long' }))

        const baseDate = new Date(Date.UTC(2017, 0, 2)); // just a Monday
        for(let i = 0; i < 7; i++)
        {       
            tempMonth.push(baseDate.toLocaleDateString('default', { weekday: 'short' }));
            baseDate.setDate(baseDate.getDate() + 1);       
        }

        if(nextDays.getDay() !== 1) {
            let NumberOfDaysToSkip = (nextDays.getDay() - 1) > 0  ? (nextDays.getDay() - 1) : 6
            for(let i = 0; i < NumberOfDaysToSkip; i++) {
                tempMonth.push(0)
            }
        }

        while(date.getMonth() === curMonth) {
            nextDays.setDate(date.getDate() + count);

            tempMonth.push(nextDays.getFullYear() + '-' + ("0" + (nextDays.getMonth() + 1)).slice(-2) + '-' + ("0" + nextDays.getDate()).slice(-2))
            count++;
            nextDays.setDate(date.getDate() + count);
            curMonth = nextDays.getMonth()
        }
        console.log(tempMonth)
        setMonth(tempMonth)
    }, [countMonth])

    const fetchcalendar = useCallback(async() => {
        try {
            const fetched = await request('/api/getCalendarWithEvents/' + id, 'GET', null, {
                'Authorization': token
            })
            setCalendar(fetched)

            let eventsDatesTemp = [];
            if(fetched.events.length !== 0) {
                eventsDatesTemp = fetched.events.map((event) => {
                    return event.event_date.split(' ')[0]
                })
            }
            setEventsDates(eventsDatesTemp)

        }
        catch (e) {}
    }, [token, request, id]);

    const createEventOnTrue = (event) => {
        event.preventDefault()
        if(event.target.id.indexOf('-') !== -1) {
            setEventStart(event.target.id)
            setCreteEvetn(true)
        }
    }

    const createEventOnFalse = (event) => {
        event.preventDefault()
        setCreteEvetn(false)
    }

    useEffect( () => {
        fetchcalendar();
    }, [fetchcalendar]);

    if(loading  || !calendar || !eventsDates) {
        return (
            <>
                <Loader />
            </>
        )
        
    }

    return (
    <>{ creteEvetn === true ? < CreateEvent createEventOnFalse={createEventOnFalse} eventStart={eventStart}/> :
        <div className="CalendarPageGrid">

            <div className="PrevMonth" onClick={PrevMonth}>
                <li className="waves-effect">
                    <FiArrowLeft className="MonthNextOrPrevArrow"/>
                </li>
            </div>

            <div className="card-panel teal DaysBlockGrid">
                {
                    
                    month.map((day, index) => {
                        const className = eventsDates.indexOf(day) !== -1 ? "DayBlock DayBlockWithEvent" : "DayBlock"
                        return (
                            <div key={index} className={ index === 0 ? "MonthBlock" : className} onClick={createEventOnTrue} id={day} >
                                <p id={day}>
                                    {day !== 0  ? day.indexOf('-') !== -1 ? parseInt(day.split('-').slice(-1)[0]) : day : ''}
                                </p>
                            </div>
                        )
                    })
                    
                }
            </div>
            <div className="NextMonth" onClick={NextMonth}>
                <li className="waves-effect">
                    <FiArrowRight className="MonthNextOrPrevArrow"/>
                </li>
            </div>

        </div>
    }</>
    );
}