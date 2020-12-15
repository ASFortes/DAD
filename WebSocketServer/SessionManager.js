/*jshint esversion: 6 */




class SessionManager {
    constructor() {
        this.users = new Map()
    }

    getUserSession (userID) {
        return this.users.get(userID)
    }

    addUserSession (user, socketID) {
        // If already exists a session for this user ID, reuse the session 
        let userSession = this.getUserSession(user.id)
        if (userSession) {
            userSession.user = user
            userSession.socketID = socketID
            return
        }
        // Otherwise, create a new session
        userSession = {
            user: user,
            socketID: socketID
        }
        this.users.set(user.id, userSession)
        return userSession
    }

    removeUserSession (userID) {
        let userSession = this.getUserSession(userID)
        if (!userSession) {
            return null
        }
        let cloneUserSession = Object.assign({}, userSession)
        this.users.delete(cloneUserSession.user.id)
        return cloneUserSession
    }

    removeSocketIDSession (sessionID) {
        for (let [userID, userSession] of this.users) {
            if (userSession.socketID === sessionID) {
                let cloneUserSession = Object.assign({}, userSession)
                this.users.delete(cloneUserSession.user.id)
                return cloneUserSession
            }
        }
        return null
    }
}

module.exports = SessionManager











/*


class LoggedUsers {
	constructor() {
        this.users = new Map();
    }

    userInfoByID(userID) {
    	return this.users.get(userID);
    }

    userInfoBySocketID(socketID) {
        for (var [userID, userInfo] of this.users) {
            if (userInfo.socketID == socketID)  {
                return userInfo;                
            }
        }
        return null;
    }

    addUserInfo(user, socketID) {
        let userInfo = {user: user, socketID: socketID};
    	this.users.set(user.id, userInfo);
    	return userInfo;
    }

    removeUserInfoByID(userID) {
        let userInfo = this.userInfoByID(userID);
        if (userInfo === null) {
            return null;
        }
        let cloneUserInfo = Object.assign({}, userInfo);
        this.users.delete(userID);
        return cloneUserInfo;
    }

    removeUserInfoBySocketID(socketID) {
        let userInfo = this.userInfoBySocketID(socketID);
        if (userInfo === null) {
            return null;
        }
        let cloneUserInfo = Object.assign({}, userInfo);
        this.users.delete(userInfo.user.id);
        return cloneUserInfo;
    }

    getUsersInfoOfDepartment(departmentID) {
        let usersInfo= [];
        for (var [userID, userInfo] of this.users) {
            if (userInfo.user.department_id == departmentID)  {
                usersInfo.push(userInfo);   
            }
        }
        return usersInfo;
    }
}

module.exports = LoggedUsers;
*/