import React, { useCallback, useContext, useEffect, useState } from 'react';
import { useHttp } from '../hooks/http.hook';
import {AuthContext} from '../context/AuthContext'
import { Loader } from '../components/Loader';
import { Calendar } from '../components/Calendar';


export const HomePage = () => {
    const [calendars, setCalendars] = useState();
    const {loading, request} = useHttp();
    const {token} = useContext(AuthContext);
    const [month, setMonth] = useState();

    useEffect(()=>{
        const date = new Date();
        const nextDays = new Date(date)
        nextDays.setDate(1);
        let curMonth = date.getMonth()
        const tempMonth = [];
        let count = -(date.getDate() -  1);

        tempMonth.push(date.toLocaleString('default', { month: 'long' }))

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

        setMonth(tempMonth)
    }, [])

    const fetchCalendars = useCallback(async() => {
        try {
            const fetched = await request('api/getCalendarsWithEvents', 'GET', null, {
                'Authorization': token
            })
            setCalendars(fetched)
            
        }
        catch (e) {}
    }, [token, request]);

    useEffect( () => {
        fetchCalendars();
    }, [fetchCalendars]);

    if(loading  || !calendars) {
        return <Loader />
    }

    return (
        <div className="BlockForCalendars">{
            calendars.map((calendar) => {
                return (
                    <div className="CalendarBlock" key={calendar.calendar.id}><Calendar calendar={calendar} month={month}/></div>
                    
                );
            })
        }</div>
    );

}