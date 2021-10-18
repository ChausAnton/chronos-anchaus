import React, { useCallback, useContext, useEffect, useState } from 'react';
import { useHttp } from '../hooks/http.hook';
import {AuthContext} from '../context/AuthContext'
import { Loader } from '../components/Loader';
import { useParams } from "react-router-dom";
import { FiArrowRight, FiArrowLeft} from "react-icons/fi";

export const CalendarPage = () => {
    const [calendar, setCalendar] = useState();
    const {loading, request} = useHttp();
    const {token} = useContext(AuthContext);
    const [month, setMonth] = useState();
    const [eventsDates, setEventsDates] = useState();
    const [countMonth, setCountMonth] = useState(new Date().getMonth());
    const {id} = useParams();

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

            tempMonth.push(nextDays.getDate())
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
                    return parseInt(event.event_date.split(' ')[0].split('-').slice(-1)[0])
                })
            }
            setEventsDates(eventsDatesTemp)
        }
        catch (e) {}
    }, [token, request, id]);

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

            <div className="NextMonth" onClick={NextMonth}>
                <li className="waves-effect">
                    <FiArrowRight className="MonthNextOrPrevArrow"/>
                </li>
            </div>

        </div>
    );
}