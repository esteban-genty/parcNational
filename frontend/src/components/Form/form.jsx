function Form() {
  return (
    <form action="">
      <label htmlFor="fname">First name:</label>
      <input type="text" id="fname" name="fname" defaultValue="John" />

      <label htmlFor="lname">Last name:</label>
      <input type="text" id="lname" name="lname" defaultValue="Doe" />

      <input type="submit" value="Submit" />
    </form>
  );
}

export default Form;
