import React, { useCallback, useContext, useEffect, useState } from 'react';
import { useHttp } from '../hooks/http.hook';
import {AuthContext} from '../context/AuthContext'
import { Loader } from '../components/Loader';



export const HomePage = () => {
    const [calendars, setCalendars] = useState();
    const {loading, request} = useHttp();
    const {token} = useContext(AuthContext);




    const fetchCalendars = useCallback(async() => {
        try {
            const fetched = await request('api/getCalendarsForUser', 'GET', null, {
                'Authorization': token
            })
            setCalendars(fetched)
        }
        catch (e) {}
    }, [token, request]);

    useEffect( () => {
        fetchCalendars();
    }, [fetchCalendars]);

 

    if(loading) {
        return <Loader />
    }

    return (
        <p>some text</p>
    );

}