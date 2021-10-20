import React, {useState, useEffect, useContext} from 'react';
import { useHttp } from '../hooks/http.hook';
import { useMessage } from '../hooks/message.hook';
import { AuthContext } from "../context/AuthContext";
import { FiArrowLeft, FiSend } from "react-icons/fi";
import { useParams } from "react-router-dom";
import Select from 'react-select';


export const CreateEvent = ({createEventOnFalse, eventStart}) => {
    const message = useMessage();
    const {token} = useContext(AuthContext);
    const {loading, error, request, clearError} = useHttp()
    const {id} = useParams();

    const [form, setForm] = useState ( {
        event_content: '', event_title: '', event_category: 'task', event_date: eventStart, event_duration: eventStart
    });

    useEffect( () => {
        message(error);
        clearError();
    }, [error, message, clearError]);

    useEffect( () => {
        window.M.updateTextFields()
    }, []);

    const chengeHandler = event => {
        if(event.target)
            setForm({...form, [event.target.name]: event.target.value})
        else {
            form.event_category = event.value
        }
    };

    const CreateEventHandler = async(event) => {
        try {
            event.preventDefault();
            await request('/api/createEventForCalendar/' + id, 'POST', {...form}, {
                'Authorization': token
            })
            window.location.reload();
            createEventOnFalse()
        }
        catch (e) {}
    };

    const options = ['arrangement', 'reminder', 'task'].map((category) => {
        return {value: category, label: category}
    })

    const SelectStyle = {
        option: (provided) => ({
            ...provided,
            color: '#000000',
            padding: 10,
        }),
        valueContainer: base => ({
            ...base,
            color: 'white',
        }),

        multiValueLabel: base => ({
            ...base,
            backgroundColor: "#1976d2",
            color: "#FFFFFF"
        }),
        multiValueRemove: base => ({
            ...base,
            color: "#000000"
        }),
        control: (base, state) => ({
            ...base,
            boxShadow: "none",
            border: `2px solid ${state.isFocused ? "#ffeb3b" : "#1976d2"}`,
            '&:hover': {
                border: `2px solid ${state.isFocused ? "#ffeb3b" : "#1976d2"}`
            }
        })
    }

    return (
        <div>
            <div className="card blue darken-1">
                <div className="card-content white-text">
                    <button className="btn-floating btn-large waves-effect waves-light grey lighten-1 EditPostButtonBack" onClick={createEventOnFalse}> 
                        <FiArrowLeft className="FiArrowLeftSizeEditProfile"/>
                    </button>
                    <span className="card-title center">Create Event</span>
                    <div>
                        <div className="input-field">
                            <input placeholder="input event title" 
                                id="event_title" 
                                type="text" 
                                name="event_title" 
                                className="yellow-input white-text" 
                                onChange={chengeHandler} 
                                />

                            <label htmlFor="event_title">event title</label>
                        </div>
                        <div className="input-field">
                            <input placeholder="input event content" 
                                id="event_content" 
                                type="text" 
                                name="event_content" 
                                className="yellow-input white-text" 
                                onChange={chengeHandler} 
                                />

                            <label htmlFor="event_content">event content</label>
                        </div>
                        <div className="input-field">
                            <input placeholder="input event date" 
                                id="event_date" 
                                type="text" 
                                name="event_date" 
                                className="yellow-input white-text" 
                                onChange={chengeHandler} 
                                defaultValue={eventStart}
                                />

                            <label htmlFor="event_date">event date</label>
                        </div>
                        <div className="input-field">
                            <input placeholder="input event duration" 
                                id="event_duration" 
                                type="text" 
                                name="event_duration" 
                                className="yellow-input white-text" 
                                onChange={chengeHandler} 
                                defaultValue={eventStart}
                                />

                            <label htmlFor="event_duration">event duration</label>
                        </div>
                        <div className="input-field">
                                <p className="categories">event category</p>
                                <Select options={options}
                                placeholder={"Select event category"}
                                closeMenuOnSelect={true}
                                id="event_category" 
                                name="event_category" 
                                styles={SelectStyle}
                                defaultValue={options[2]}
                                onChange={chengeHandler}
                                />
                                <label htmlFor="event_category" hidden>event category</label>
                        </div>
                    </div>
                </div>
                <div className="card-action">
                    <div className="center"> 
                        <button className="btn waves-light red" onClick={CreateEventHandler}  disabled={loading}>Submit
                            <FiSend className="FiSendSizeEditProfile"/>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}