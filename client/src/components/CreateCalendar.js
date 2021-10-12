import React, { useContext, useEffect, useState } from 'react';
import { AuthContext } from '../context/AuthContext';
import { useHttp } from '../hooks/http.hook';
import { useMessage } from '../hooks/message.hook';
import { FiArrowLeft, FiSend} from "react-icons/fi";

export const CreateCalendar = ({setCreateNewCalendarStatusOnFalse}) => {
   
    const message = useMessage();

    const {token} = useContext(AuthContext);
    const {loading, error, request, clearError} = useHttp()

    const [form, setForm] = useState ( {
        calendar_title: ''
    });

    useEffect( () => {
        message(error);
        clearError();
    }, [error, message, clearError]);

    useEffect( () => {
        window.M.updateTextFields()
    }, []);

    const chengeHandler = event => {
        setForm({...form, [event.target.name]: event.target.value})
        
    };

    const CalendarHandler = async(event) => {
        try {
            event.preventDefault();
            await request('/api/CreateCalendar', 'POST', {...form}, {'Authorization': token})
            window.location.reload();
            setCreateNewCalendarStatusOnFalse()

        }
        catch (e) {}
    };

    return (
        <div>
            <div className="card blue darken-1">
                <button className="btn-floating btn-large waves-effect waves-light grey lighten-1 ButtonBack" onClick={setCreateNewCalendarStatusOnFalse}> 
                    <FiArrowLeft className="FiArrowLeftSizeEditProfile"/>
                </button>
                <div className="card-content white-text">
                    <div className="input-field CreateCalendarInput">
                        <input placeholder="input calendar title" 
                            id="calendar_title" 
                            type="text" 
                            name="calendar_title" 
                            className="yellow-input white-text" 
                            onChange={chengeHandler} 
                            />

                        <label htmlFor="title">title</label>
                    </div>
                </div>
                <div className="card-action center-align">
                    <button className="btn waves-light red" onClick={CalendarHandler} disabled={loading}>Submit
                        <FiSend className="FiSendSizeEditProfile"/>
                    </button>
                </div>
            </div>
        </div>
    );
  }