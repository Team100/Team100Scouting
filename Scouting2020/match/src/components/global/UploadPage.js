import React, {Component} from "react";


import "../../assets/css/materialIcons.css";
import "../../assets/css/matchScoutGlobal.css";
import {Form, Icon, Input, Button, Select, Row, Col, AutoComplete, message, Card} from 'antd';
import 'antd/dist/antd.css'; // or 'antd/dist/antd.less'

import Lottie from 'react-lottie';
import animationData from '../../assets/lottie/2615-success.json';

const {TextArea} = Input;


const {Option} = Select;
const {Meta} = Card;
const InputGroup = Input.Group;


export default class UploadPage extends Component {
    onChange = ({target: {value}}) => {
        this.setState({value});
    };

    componentWillMount() {
        this.setState({
            value: '',
        });
    }

    onFinish() {
        this.props.callback(this.state);
    }


    render() {
        var defaultOptions = {
            loop: false,
            autoplay: true,
            animationData: animationData,
            rendererSettings: {
                preserveAspectRatio: 'xMidYMid slice'
            }
        };

        return (
            <div>
                <code>{JSON.stringify(this.props.data)}</code>
                <div><Lottie options={defaultOptions}
                             width={400}
                             height={400}

                             isStopped={false}
                             isPaused={false}

                             eventListeners={[
                    {
                    eventName: "complete",
                    callback: {console.log("the animation completed:")},
                             }
                    ]}
                />
            </div>

    </div>


    )
        ;

    }
}
