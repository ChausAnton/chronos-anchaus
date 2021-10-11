import React, {useContext} from "react";
import {AuthContext} from '../context/AuthContext'
import { useHistory } from "react-router-dom";
import { useHttp } from '../hooks/http.hook';

export const Navbar = () => {
    const auth = useContext(AuthContext);
    const history = useHistory();
    const {request} = useHttp();
    const {token} = useContext(AuthContext);

    const logoutHandler = event => {
        event.preventDefault();
        request('/api/logout', 'GET', null, {'Authorization': token})
        auth.logout();
        history.push('/')
    };
    
    return (
        <nav>
        <div className="nav-wrapper blue darken-2">
          <a href="/home" className="brand-logo">Logo</a>
          <ul id="nav-mobile" className="right">
            <li><a className="blue darken-3" href="/home" onClick={logoutHandler}>Logout</a></li>
          </ul>
        </div>
      </nav>
    );
}
