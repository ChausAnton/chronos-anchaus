import { useCallback, useState } from "react";

const storageName = 'userData'

export const useAuth = () => {
    const data = JSON.parse(localStorage.getItem('userData')) || [];
    const [token, setToken] = useState(null || data.token)

    const [userId, setUserId] = useState(null || data.userId);

    const login = useCallback( (jwtToken, Id) => {
        setToken(jwtToken);
        setUserId(Id);
        localStorage.setItem(storageName, JSON.stringify( {
            userId: Id, token: jwtToken
        }))
    }, []);

    const logout = useCallback( () => {
        setToken(null);
        setUserId(null);
        localStorage.removeItem(storageName);
    }, []);    

    // useEffect( () => {
    //     const data = JSON.parse(localStorage.getItem(storageName));
    //     if(data && data.token) {
    //         login(data.token, data.userId)
    //     }
    // }, [login]);


    return {token, login, logout, userId};
};