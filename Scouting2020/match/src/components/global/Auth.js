import React, {Component} from "react";


import "../../assets/css/materialIcons.css";
import "../../assets/css/auth.css";
import {Form, Icon, Input, Button, Select, Row, Col, AutoComplete, message, Card} from 'antd';
import 'antd/dist/antd.css'; // or 'antd/dist/antd.less'






export default class Auth extends Component {

    componentWillMount() {
        this.setState({bkg: this.getRandomBKG()})
    }


    images = [
        "brazil",
        "stars",
        "singapore",
        "hk"
    ]


    getRandomBKG(){
        return this.images[Math.floor(Math.random() * this.images.length)]
    }

    render() {


      return(
          <div className={`background ${this.state.bkg}`}>
              <div className="flexItem pageLeft">
                  <h4>Welcome!</h4>
                  <form action="https://duckduckgo.com">
                      <input type="text" id="q" name="q" placeholder="User ID" className="authBox"/><br />
                      <div className={"logInButton"} onClick={()=>{console.log("Authentication")}}>Log In</div>

                  </form>
              </div>

              </div>
      );

    }
}
