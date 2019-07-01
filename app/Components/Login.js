import React, { Component } from 'react';
import { Image, StyleSheet, Text, View, TextInput, KeyboardAvoidingView, TouchableOpacity, AsyncStorage } from 'react-native';
import AnimateLoadingButton from 'react-native-animate-loading-button';
import DeviceInfo from 'react-native-device-info';
import './Global'

export default class Login extends Component {

    constructor(props) {
        super(props);
        this.state = {
            username: '',
            password: '',
        }
    }
    render() {
        return (
            <KeyboardAvoidingView style={styles.wrapper}>
                <View style={styles.container}>
                    <Image source={require('../assets/logo.png')} style={styles.header} />
                    <TextInput
                        style={styles.textinput} placeholder="Username"
                        onChangeText={(username) => this.setState({ username })}
                        underlineColorAndroid='#263238'
                    />
                    <TextInput
                        style={styles.textinput} placeholder="Password"
                        onChangeText={(password) => this.setState({ password })}
                        underlineColorAndroid='#263238'
                        secureTextEntry={true}
                    />
                    <AnimateLoadingButton
                        ref={c => (this.loadingButton = c)}
                        width={300}
                        height={50}
                        title="SUBMIT"
                        titleFontSize={16}
                        titleColor="rgb(255,255,255)"
                        backgroundColor="#263238"
                        borderRadius={4}
                        onPress={this._login.bind(this)}
                    />
                </View>
            </KeyboardAvoidingView>
        );
    }
    
    _login = () => {
        var url = global.url + 'applogin.php'
        var data = new FormData()
        data.append('username', this.state.username)
        data.append('password', this.state.password)
        this.loadingButton.showLoading(true);
        fetch(url, {
            method: 'POST',
            body: data  
        }).then(response => response.json())
            .then((res) => {
                this.loadingButton.showLoading(false);
                if (res.status === 'Successfull') {
                    AsyncStorage.setItem('user', this.state.username);
                    AsyncStorage.setItem('pass', this.state.password);
                    AsyncStorage.setItem('uid', DeviceInfo.getUniqueID());
                    this.props.navigation.navigate('Profile');
                }
                else {
                    this.loadingButton.showLoading(false);
                    alert("Please Check Your Username & Password");
                }
            }).catch((error) => {
               alert("Please Check Your Internet Connection");
            });
    }
}

const styles = StyleSheet.create({
    wrapper: {
        flex: 1,
    },
    container: {
        backgroundColor: '#FFFFFF',
        flex: 1,
        alignItems: 'center',
        justifyContent: 'center',
        paddingLeft: 40,
        paddingRight: 40,
    },
    header: {
        width:150,
        height:150,
        alignSelf: 'center',
        marginBottom: 60,
    },
    textinput: {
        color: '#263238',
        alignSelf: 'stretch',
        padding: 16,
        marginBottom: 20,
    },
   
})