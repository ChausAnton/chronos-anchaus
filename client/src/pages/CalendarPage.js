import React, { useCallback, useContext, useEffect, useState } from 'react';
import { useHttp } from '../hooks/http.hook';
import {AuthContext} from '../context/AuthContext'
import { Loader } from '../components/Loader';
import { useParams } from "react-router-dom";
import { FiArrowRight, FiArrowLeft} from "react-icons/fi";
import { CreateEvent } from '../components/CreateEvent';
import { AiOutlineClose } from "react-icons/ai";
import { FiPlus } from "react-icons/fi"

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

    const [EventData, setEventData] = useState ([]);

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

    const setEventStartOrShowEventInfo = (event) => {
        event.preventDefault()
        setEventStart(event.target.id)
            //setCreteEvetn(true)

        const EventsOnDay = []
        for(let index in calendar.events) {
            if((calendar.events[index].event_date.split(' ')[0]).localeCompare(event.target.id) === 0) {
                EventsOnDay.push(calendar.events[index])
            }
        }
        setEventData(EventsOnDay)
    }



    const CloseRevealCard = () => {
        document.getElementById('CloseRevealCard').click()
    }

    const createEventOnTrue = (event) => {
        event.preventDefault()
        setCreteEvetn(true)
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
    <>
        <div className="CalendarPageGrid">
            
            <div className="PrevMonth" onClick={PrevMonth}>
                <li className="waves-effect">
                    <FiArrowLeft className="MonthNextOrPrevArrow"/>
                </li>
            </div>
                <div className="card teal DaysBlockGrid">
                    {
                        
                        month.map((day, index) => {
                            const className = eventsDates.indexOf(day) !== -1 ? "DayBlock DayBlockWithEvent activator" : "DayBlock"
                            return (
                                <div key={index} className={ index === 0 ? "MonthBlock" : className} onClick={setEventStartOrShowEventInfo} id={day} >
                                    <p id={day} className="activator">
                                        {day !== 0  ? day.indexOf('-') !== -1 ? parseInt(day.split('-').slice(-1)[0]) : day : ''}
                                    </p>
                                </div>
                            )
                        })
                        
                    }
                    <div className="card-reveal">
                        <span className="card-title" id="CloseRevealCard"><AiOutlineClose onClick={CloseRevealCard}/></span>
                        {
                            EventData.map((event, index) => {
                                return (
                                    <div className="card blue darken-1" key={index}>
                                        <div className="card-content white-text">
                                            <span className="card-title center">{event.event_title}</span>
                                            <div className="EventCategory">
                                                <div className="chip">
                                                    <span>{event.event_category}</span>
                                                </div>
                                            </div>
                                            <div className="EventContent">
                                                <span>{event.event_content}</span>
                                            </div>
                                            <div className="EventDataAndDuration center">
                                                <span>{event.event_date.split(' ')[0]} </span>
                                                <FiArrowRight className="FiArrowRightDateToDuration"/>
                                                <span> {event.event_duration.split(' ')[0]}</span>
                                            </div>
                                        </div>
                                    </div>
                                )
                            })
                        }
                        <div className="center">
                            <button className={('btn-floating btn-large cyan ' + (EventData.length === 0 ? 'pulse' : ''))} onClick={createEventOnTrue}><FiPlus className="FiPlusSizeEditProfile"/></button>
                        </div>
                        
                    </div>
            </div>
                
            <div className="NextMonth" onClick={NextMonth}>
                <li className="waves-effect">
                    <FiArrowRight className="MonthNextOrPrevArrow"/>
                </li>
            </div>
                


        </div></>
    }</>
    );
}