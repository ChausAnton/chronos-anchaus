import React, {useState, useEffect, useContext} from 'react';
import { useHttp } from '../hooks/http.hook';
import { useMessage } from '../hooks/message.hook';
import { useHistory } from "react-router-dom";
import { AuthContext } from "../context/AuthContext";

export const RegisterPage = () => {
    const message = useMessage();
    const {token} = useContext(AuthContext);
    const {loading, error, request, clearError} = useHttp()

    const [form, setForm] = useState ( {
        email: '', login: '', real_name: '', password: '', password_confirmation: ''
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
    };

    const registerHandler = async() => {
        try {
            await request('/api/register', 'POST', {...form})
        }
        catch (e) {}
    };

    const history = useHistory();
    const signInHandler = event => {
        event.preventDefault();
        history.push('/')
    };


    return (
        <div>
            <div className="card blue darken-1">
                <div className="card-content white-text">
                    <span className="card-title">registration</span>
                        <div>
                        <div className="input-field">
                                <input placeholder="input email" 
                                    id="email" 
                                    type="text" 
                                    name="email" 
                                    className="yellow-input white-text" 
                                    onChange={chengeHandler} 
                                    />

                                <label htmlFor="email">email</label>
                            </div>
                            <div className="input-field">
                                <input placeholder="input login" 
                                    id="login" 
                                    type="text" 
                                    name="login" 
                                    className="yellow-input white-text" 
                                    onChange={chengeHandler} 
                                    />

                                <label htmlFor="login">Login</label>
                            </div>
                            <div className="input-field">
                                <input placeholder="input real name" 
                                    id="real_name" 
                                    type="text" 
                                    name="real_name" 
                                    className="yellow-input white-text" 
                                    onChange={chengeHandler} 
                                    />

                                <label htmlFor="real_name">real name</label>
                            </div>
                            <div className="input-field">
                                <input placeholder="input password" 
                                    id="password" 
                                    type="password" 
                                    name="password" 
                                    className="yellow-input white-text" 
                                    onChange={chengeHandler} 
                                    />

                                <label htmlFor="password">password</label>
                            </div>
                            <div className="input-field">
                                <input placeholder="repeat password" 
                                    id="password_confirmation" 
                                    type="password" 
                                    name="password_confirmation" 
                                    className="yellow-input white-text" 
                                    onChange={chengeHandler} 
                                    />

                                <label htmlFor="password_confirmation">repeat password</label>
                            </div>
                        </div>
                </div>
                <div className="card-action">
                    {token ? <></> : <button className="btn yellow darken-4 regButtonMargin" onClick={signInHandler} disabled={loading}>sign in</button>}
                    
                    <button className="btn grey lighten-1"
                        onClick={registerHandler}
                        disabled={loading}
                    >
                    sign up</button>

                </div>
            </div>
        </div>
    );
}