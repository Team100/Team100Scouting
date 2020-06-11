import React, {Component} from "react";


import "../../assets/css/materialIcons.css";
import "../../assets/css/matchScoutGlobal.css";
import {Card, Input, Select} from 'antd';
import 'antd/dist/antd.css'; // or 'antd/dist/antd.less'
import Lottie from 'react-lottie';
import success from '../../assets/lottie/2615-success.json';
import failure from '../../assets/lottie/5707-error.json';
import loading from '../../assets/lottie/315-loader-ring';

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
            currentState: 'success'
        });
    }

    onFinish() {
        this.props.callback(this.state);
    }


    render() {


        if (this.state.currentState == "success") {
            console.info("SUCCESS");
            var defaultOptions = {
                loop: false,
                autoplay: true,
                animationData: success,
                rendererSettings: {
                    preserveAspectRatio: 'xMidYMid slice'
                }
            };

            return (
                <div>
                    <div><Lottie options={defaultOptions}
                                 width={400}
                                 height={400}

                                 isStopped={false}
                                 isPaused={false}

                                 eventListeners={[
                                     {
                                         eventName: "complete",
                                         callback: () => {
                                             this.props.callback();
                                         },
                                     }
                                 ]}
                    />
                    </div>

                </div>


            );
        } else if (this.state.currentState == "failure") {
            var defaultOptions = {
                loop: false,
                autoplay: true,
                animationData: failure,
                rendererSettings: {
                    preserveAspectRatio: 'xMidYMid slice'
                }
            };

            return (
                <div>
                    <div><Lottie options={defaultOptions}
                                 width={400}
                                 height={400}

                                 isStopped={false}
                                 isPaused={false}

                                 eventListeners={[
                                     {
                                         eventName: "complete",
                                         callback: () => {
                                             console.log("Error Complete");
                                         },
                                     }
                                 ]}
                    />
                    </div>

                </div>


            );
        } else {
            var defaultOptions = {
                loop: true,
                autoplay: true,
                animationData: loading,
                rendererSettings: {
                    preserveAspectRatio: 'xMidYMid slice'
                }
            };

            return (
                <div>
                    <div><Lottie options={defaultOptions}
                                 width={400}
                                 height={400}

                                 isStopped={false}
                                 isPaused={false}

                                 eventListeners={[
                                     {
                                         eventName: "complete",
                                         callback: () => {
                                             console.log("Loading Complete")
                                         },
                                     }
                                 ]}
                    />
                    </div>

                </div>


            );
        }


    }
}
