import React, {Component} from "react";


import "../../assets/css/materialIcons.css";
import "../../assets/css/auth.css";
import 'antd/dist/antd.css'; // or 'antd/dist/antd.less'


export default class Auth extends Component {

    images = [
        "brazil",
        "stars",
        "singapore",
        "hk"
    ]

    componentWillMount() {
        this.setState({bkg: this.getRandomBKG()})
    }

    getRandomBKG() {
        return this.images[Math.floor(Math.random() * this.images.length)]
    }

    render() {


        return (
            <div className={`background ${this.state.bkg}`}>
                <div className="flexItem pageLeft">
                    <h4>Welcome!</h4>
                    <form action="https://duckduckgo.com">
                        <input type="text" id="q" name="q" placeholder="User ID" className="authBox"/><br/>
                        <div className={"logInButton"} onClick={() => {
                            console.log("Authentication")
                        }}>Log In
                        </div>

                    </form>
                </div>

            </div>
        );

    }
}
