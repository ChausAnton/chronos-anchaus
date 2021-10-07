import React, { useCallback, useContext, useEffect, useState } from 'react';
import { useHttp } from '../hooks/http.hook';
import {AuthContext} from '../context/AuthContext'
import { Loader } from '../components/Loader';
import { PostsList } from '../components/PostsList';
import { useParams } from "react-router-dom";
import { useHistory } from "react-router-dom";


export const HomePage = () => {
    const {page, category, SearchField} = useParams();
    const [calendars, setCalendars] = useState();
    const {loading, request} = useHttp();
    const {token} = useContext(AuthContext);
    const auth = useContext(AuthContext);
    const history = useHistory();



    const fetchCalendars = useCallback(async() => {
        try {
            const fetched = await request('api/getCalendarsForUser', 'GET', null, {
                'Authorization': token
            })
            setCalendars(fetched)
        }
        catch (e) {}
    }, [token, request, page, category, SearchField]);

    useEffect( () => {
        fetchCalendars();
    }, [fetchCalendars]);

    const logoutHandler = event => {
        event.preventDefault();
        request('/api/logout', 'POST', {'Authorization': token})
        auth.logout();
        history.push('/')
    };

    if(loading) {
        return <Loader />
    }

    return (
        <li><a className="blue darken-3" href="/home" onClick={logoutHandler}>Logout</a></li>
    );

    // return (
    //     <>
    //         {!loading && <PostsList posts={posts} category={category} SearchField={SearchField}/>}
    //     </>
    // );
}